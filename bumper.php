<?php

require_once('includes/dbWrapper.php');

require_once('includes/bumper.config');

error_reporting(0);

$db = new dbWrapper(DBHOST, DBUSER, DBPASS, DBNAME, true);

//************************************  Get the current timestamp and query for all reminders set to send before now

$now=time();

$r = $db->q("SELECT * FROM bumpQueue WHERE timeToSend < ?", 'i', $now);

//************************************  Set the email headers

$headers  = 'MIME-Version: 1.0' . "\r\n" .

			'From: Bumper.cc' . "\r\n";

$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

//************************************  loop through reminders to be sent and send them

foreach($r as $key=>$email) :

mail($email['fromEmail'], $email['subject'], $email['body'], $headers);

endforeach;

$r = $db->q("DELETE FROM bumpQueue WHERE timeToSend < ?", 'i', $now);

