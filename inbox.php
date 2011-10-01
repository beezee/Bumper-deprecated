#! /usr/bin/php -q
<?php

require_once('includes/MimeMailParser.class.php');  
require_once('includes/dbWrapper.php');
require_once('includes/bumper.config');
error_reporting(0);
date_default_timezone_set('America/New_York');
//************************************   read from stdin
$fd = fopen("php://stdin", "r");
$email = "";
while (!feof($fd)) {
$email .= fread($fd, 1024);
}
fclose($fd);
  
//************************************   instantiate  
$Parser = new MimeMailParser();  
//************************************   read the email from stdin  
$Parser->setText($email);  
  
//************************************   get the email parts   
$delivered_to = $Parser->getHeader('delivered-to');  
$from = $Parser->getHeader('from');  
$subject = $Parser->getHeader('subject');  
$text = $Parser->getMessageBody('text');  
$html = $Parser->getMessageBody('html'); 

//************************************  find the address used to contact Bumper by matching domain

$headers = $Parser->getHeaders();
  foreach ($headers as $header => $value) {
    if (is_array($value))
      $value = join("\n", $value);
    if (preg_match("/\b[A-Za-z0-9._-]+@".EMAIL_DOMAIN."/", $value, $matches)) {
      $to = $matches[0];
      break;
    }
  }

//************************************  check format of from address, separate email address if needed
$fromformat = strpos($from, '<');
$fromemail = $from;
if ($fromformat != false) :
$fromsplit = explode('<', $from);
$fromsplit = str_replace('>', '', $fromsplit[1]); 
$fromemail = $fromsplit;
endif;

//************************************  check allowed email list and get timezone and email format if it checks out
$db = new dbWrapper(DBHOST, DBUSER, DBPASS, DBNAME, true);
$result = $db->q("SELECT * FROM allowedEmails WHERE allowedEmail = ?", 's', $fromemail);
if (is_array($result) and count($result) > 0) {
foreach($result as $email) :
date_default_timezone_set($email['timeZone']);
$emailformat = $email['emailFormat'];
endforeach;
$when=explode('@', $to);		
$hyphens = strpos($when[0], '-');
//************************************  check # of parameters sent
if ($hyphens == false) : 
//************************************  if one parameter, check validity
	if (strtotime($when[0]) == '') {
		$content = $to.' could not be translated into a reminder time. See the usage instructions below'."\n\n";
		include('includes/usage.inc');
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		mail($from, $subject, $content, $headers);
		die();
	}
//************************************  if valid, check if we need to set a default time
$weekdays = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
$isweekday = 0;
foreach ($weekdays as $weekday) if ($when[0] == $weekday) $isweekday = 1;
$isdate = 0;
$months = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');
foreach ($months as $month) if (stristr($when[0], $month) != FALSE and stristr($when[0], $month, true) == '') $isdate = 1;
if ($isdate == 1 or $isweekday == 1) :
	$date = date('m/d/Y', strtotime($when[0]));
	$time = '03:00PM';
	$resendtime = $date.' '.$time;	
else:
$resendtime = date('m/d/Y h:ia', strtotime($when[0]));
endif;
else : 
//************************************  if multiple parameters, loop through and check validity of each
$parameters = explode('-', $when[0]);
foreach ($parameters as $parameter) :
	if (strtotime($parameter) == '') {
		$content = $to.' could not be translated into a reminder time. See the usage instructions below'."\n\n";
		include('includes/usage.inc');
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		mail($from, $subject, $content, $headers);
		die();
	}
endforeach;
//************************************  if all valid, loop through each to build resend time
foreach ($parameters as $parameter) :
$istime = 0;
if (substr($parameter, -2) == 'am' or substr($parameter, -2) == 'pm') $istime = 1;
if ($istime == 1) $thetime = date('h:iA', strtotime(str_replace('.', ':', $parameter)));
if ($istime == 0) $thedate = date('m/d/Y', strtotime($parameter));
endforeach;
//************************************  set a default time if needed
if (!$thetime or $thetime == '') $thetime = '03:00PM';
//************************************  set the resend time
$resendtime = $thedate.' '.$thetime;
endif;
//************************************  if resend time is more than 24 hours in the past we bump it up a year	
	if (strtotime('now') - strtotime($resendtime) > 86400) :
		$nextyear = date('Y', strtotime($resendtime)) + 1;
		$adjustdaymonth = date('m/d', strtotime($resendtime));
		$adjusttime = date('h:iA', strtotime($resendtime));
		$resendtime = $adjustdaymonth.'/'.$nextyear.' '.$adjusttime;
	endif;
$timestamp = strtotime($resendtime);
$result = $db->q("INSERT INTO bumpQueue (fromEmail, subject, body, timeToSend) VALUES(?, ?, ?, ?)", 'ssss', $fromemail, $subject, $$emailformat, $timestamp );
}