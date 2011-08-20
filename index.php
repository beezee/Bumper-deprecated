<?php
$url = (!empty($_SERVER['HTTPS'])) ? 'https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
				 include("includes/class.login.php");
	$log = new logmein();     //Instentiate the class
	$log->dbconnect();        //Connect to the database
	$log->encrypt = true;          //set to true if password is md5 encrypted. Default is false.
			if($log->logincheck($_SESSION['loggedin'], "logon", "password", "useremail") == false){
				header('Location: '.$url.'login/');
				die();
			}else{
require_once('includes/dbWrapper.php');
require_once('includes/bumper.config');
require_once('includes/class_TimezoneSelector.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
 
  <head>
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Language" content="en-GB" />
    <meta name="author" content="Your Name" />
    <meta name="abstract" content="A one-liner describing your site." />
    <meta name="description" content="A longer description of your site." />
    <meta name="keywords" content="some, words, characterising, your, web, site" />
    <meta name="distribution" content="global" />
    <meta name="revisit-after" content="1 days" />	
    <meta name="copyright" content="All content (c) Your Name" />
    
    <title>Bumper</title>
    
    <style type="text/css" title="The shiny, Web 2.0 version of 'Simplicity,' a pseudo-professional style-sheet." media="all">
      @import "layout/simplicity-two-oh-three.css";
    </style>
    <!--[if IE]>
    <style type="text/css" media="all">
      @import "layout/ie-diff.css";
    </style> 
    <![endif]-->
	<link href="css/style.css" media="screen" rel="stylesheet" type="text/css"/>
    <link href="css/uniform.css" media="screen" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="layout/print.css" media="print" />
	<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/themes/flick/jquery-ui.css" media="screen" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="http://wherever-your-favicon-is.org/favicon.png"  />
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script> 
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.js"></script> 
    <link rel="icon" href="http://wherever-your-favicon-is.org/favicon.png" />
    <script type="text/javascript">
	function validateEmail(email) 
				{ 
				 var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/ 
				 return email.match(re) 
				}
		$(document).ready(function() {
				$('#logmeout').click(function() {
						var data = {
							action : 'logout'
							}
					$.post('login/login.php', data, function(response) {
						window.location = '<?php echo $url; ?>'
						});
					});
				$('#addAllowedEmail').click(function() {
					var validemail = validateEmail($('#newAllowedEmail').val());
					if (validemail == null) {
						$('#formerrortext').text('Please use a valid email address');
						$( "#formerror" ).dialog({
								height: 140,
								modal: true
							});
						return; 
						}
					var data = {
						action : 'addAllowedEmail',
						newEmail : $('#newAllowedEmail').val()
						};
					$.post( 'admin/allowedEmails.php', data, function(response) {
						if (response == 'That email address is already on the allowed list.') {
							$('#formerrortext').text(response);
								$( "#formerror" ).dialog({
										height: 140,
										modal: true
									});
						return; 
						} else {
						    $('#newAllowedEmail').val('');
							$('#allowedEmailList').html(response);
							}
						});
					});
				 $('.removeEmail').live('click', function() {
					$(this).parent().fadeOut('slow');
						var removeTheEmail = $(this).attr('id');
						var data = {
								action : 'removeAllowedEmail',
								removeEmail : removeTheEmail
								};
						$.post( 'admin/allowedEmails.php', data, function(response) {
								});
					});
			$('.tabNav').click(function() {
				var activeTab = $(this).attr('id').substr(4);
					$('.tabli').removeClass('active');
					$(this).parent().addClass('active');
					$('.tab').fadeOut('slow', function() {
						});
					$('#'+activeTab).fadeIn('slow');
				});
				
			$('.removeReminder').live('click', function() {
					$(this).parent().fadeOut('slow');
						var removeTheReminder = $(this).attr('id').substring(5);
						var data = {
								action : 'removeReminder',
								removeBump : removeTheReminder
								};
						$.post( 'admin/manageReminders.php', data, function(response) {
								});
					});
			$('.tzselect').live('change', function() {
						var updateTheEmail = $(this).attr('id').substring(2);
						var theTimeZone = $(this).val();
						var data = {
								action : 'setEmailTimeZone',
								allowedEmail : updateTheEmail,
								timeZone : theTimeZone
								};
						$.post( 'admin/allowedEmails.php', data, function(response) {
								});
					});
			 $('.emailFormat').live('change', function() {
						var updateTheEmail = $(this).attr('id').substring(2);
						var theFormat = $(this).val();
						var data = {
								action : 'setEmailFormat',
								allowedEmail : updateTheEmail,
								format : theFormat
								};
						$.post( 'admin/allowedEmails.php', data, function(response) {
								});
					});
		});
	</script>
  </head>
  
  <body>
    
    <div id="window">
      <div id="container">
	
	<div id="navigation">
	  <ul>
	    <!-- The link you call "active" will show up as a darker tab -->
	    <!--[if IE 6]><li></li><![endif]-->
	    <li class="tabli active"><a class="tabNav" id="linkallowedEmailsTab">Allowed Emails</a></li>
	    <li class="tabli"><a class="tabNav" id="linkcronJobTab">Configuration</a></li>
	    <li class="tabli"><a class="tabNav" id="linkremindersTab">How to Set Reminders</a></li>
		<li class="tabli"><a class="tabNav" id="linkremindersQueueTab">Reminder Queue</a></li>
	  </ul>
	</div>
	
	<div id="main">
	  <div id="outer-prettification">
	    <div id="inner-prettification">
	      
	      <div id="header">
		<h1 id="title"><span>Bumper Admin</span></h1>
		<a id="logmeout" style="float:right;margin-right:80px;">Logout</a>
		<br clear="all" /> 
	      </div>
		  
		  
		  <div id="formerror" style="display:none;">
			<div class="ui-state-error" id="formerrortext" style="text-align:center;font-size:18px;margin:30px auto 0;">
			Invalid login, please try again.
			</div>
		  </div>
	      
	      <div id="contents" style="overflow:hidden;">
			<div class="fancy tab" id="allowedEmailsTab" style="overflow:hidden;">
			<h2>Allowed Email Addresses</h2>
				<p style="margin-left:50px;width:80%;">These are the email addresses that can schedule reminders with your installation of Bumper.</p>
					<ul id="allowedEmailList" style="width:350px;list-style:none;">
						<?php $db = new dbWrapper('localhost', DBUSER, DBPASS, DBNAME, true);
						$result = $db->q("SELECT * FROM allowedEmails");
						foreach($result as $key=>$email) : ?>
						<li class="schmancy">
						<a class="removeEmail" style="cursor:pointer;" id="<?php echo $email['allowedEmail']; ?>" title="Remove this email">
						<img src="layout/images/delbtn.png" style="float:right;" /></a>
						<?php echo $email['allowedEmail']; 
						if ($email['timeZone'] and $email['timeZone'] != '') : $tzs = new TimezoneSelector("tzselector", $email['timeZone'], "id=\"tz".$email['allowedEmail']."\" class=\"tzselect\" style=\"width:");
						else: $tzs = new TimezoneSelector("tzselector","America/Denver", "id=\"tz".$email['allowedEmail']."\" class=\"tzselect\"");
						endif;
						$tzs->show(1, "m/d/y H:iA"); ?>
						<select class="emailFormat" id="ef<?php echo $email['allowedEmail'];?>" style="margin-left:193px;">
						<option value="text" <?php if ($email['emailFormat'] == 'text') echo 'SELECTED'; ?>>Text Emails</option>
						<option value="html" <?php if ($email['emailFormat'] == 'html') echo 'SELECTED'; ?>>HTML Emails</option>
						</select>
						</li>
						<?php endforeach; ?>
					</ul>
					<form class="TTWForm" method="post" novalidate="">           
						  <div id="field12-container" class="field f_100">
							   <label for="field12">
									Add an Email Address
							   </label>
							   <input type="text" id="newAllowedEmail" required="required">
						  </div>						   
						  <div id="form-submit" class="field f_100 clearfix submit">
							   <input id="addAllowedEmail" type="button" value="Add">
						  </div>
					 </form>
			</div>
			<div class="fancy tab" id="cronJobTab" style="overflow:hidden;display:none;">
				<h2>Configuration</h2>
					<p style="margin-left:50px; width:80%;">Add the line below to a cron job on your server. <br />When it runs Bumper will check for any 
					reminders that are past due and send them along. <br /><br />
					Keep in mind the more frequently your cron is scheduled to run, the more "real-time" your updates will be. <br />For a less 
					frequent schedule, make sure you set your reminders a little early to accommodate.</p>
						<form class="TTWForm" method="post" novalidate="">           
							   <input type="text" id="cronJob" style="text-align:center;" value="wget -O - <?php echo $url; ?>bumper.php" disabled="true" required="required">
						</form>
					<p style="margin-left:50px; width:80%;">Set your catchall email address to pipe to the script below <br /> When you send an email to your Bumper domain this will process them for the reminder queue. <br /><br />
					<span style="font-style:italic;">Note if you use shared hosting you may need to ask your hosting provider to enable catchall email addresses for you.</span>
					</p>
					<form class="TTWForm" method="post" novalidate="">           
							   <input type="text" id="pipeScript" style="text-align:center;" value="<?php echo dirname(__FILE__).'/inbox.php'; ?>" disabled="true" required="required">
						</form>
			</div>
			<div class="fancy tab" id="remindersTab" style="overflow:hidden;display:none;">
				<h2>How to Set Reminders</h2>
					<p style="margin-left:50px; width:80%;">Bumper schedules your reminders based on the email address you use to set them.<br /><br /> 
					For example if your catchall domain was bumper.cc, sending an email to thursday@bumper.cc would schedule Bumper to 
					send that email back to you next Thursday. Note that Bumper reads the BCC field for this address, so you must use 
					that field to schedule your reminder. <br /><br />
					This also means you can reply to an email and schedule a reminder at the same
					time.<br /><br />
					The list below contains examples of the six different ways you can format your scheduling request when BCC'ing Bumper to set a reminder.
					</p>
				<ul id="allowedEmailList" style="width:80%;list-style:none;">
					<li class="schmancy"><span style="font-style:italic;float:right;">Date, or date-time of day.</span>
					<br clear="all" /> <br clear="all" /> 
					BCC july14@bumper.cc to have the email sent back to you on July 14th (3PM by default.)<br /><br />
					BCC september20-3pm@bumper.cc to have the email sent back to you on September 20th at 3pm.
					</li>
					<li class="schmancy"><span style="font-style:italic;float:right;">Day of week, or day of week-time of day.</span>
					<br clear="all" /> <br clear="all" /> 
					BCC thursday@bumper.cc to have the email sent back to you on Thursday (3PM by default.)<br /><br />
					BCC tuesday-9.30am@bumper.cc to have the email sent back to you on Tuesday at 9:30 AM.
					</li>
					<li class="schmancy"><span style="font-style:italic;float:right;">Time from now, or time from now + time of day.</span>
					<br clear="all" /> <br clear="all" /> 
					BCC 2weeks2days3hours5minutes@bumper.cc to have the email sent back to you in 2 weeks, 2days, 3 hours and 5 minutes from now.<br /><br />
					BCC 1year2days10minutes@bumper.cc to have the email sent back to you in 1 year, 2days and 10 minutes from now.<br /><br />
					BCC 2months8am@bumper.cc to have the email sent back to you in 2 months at 8 am.<br /><br />
					BCC 1week2days12pm@bumper.cc to have the email sent back to you in 1 week and 2 days, at 12 noon.<br /><br />
					Note that when combining time from now + time of day, you can use years, months, weeks and days + a time of day. Time of day 
					replaces hours and minutes.
					</li>
				</ul>
			</div>
			<div class="fancy tab" id="remindersQueueTab" style="overflow-y:scroll;display:none;max-height:590px;">
				<h2>Reminder Queue</h2>
				<p style="margin-left:50px;width:80%;">These are the reminders currently scheduled in your Bumper. Times are shown in timezone:<?php $tz = date_default_timezone_get(); echo $tz; ?></p>
					<ul id="queuedBumpList" style="width:500px;list-style:none;">
						<?php $db = new dbWrapper('localhost', DBUSER, DBPASS, DBNAME, true);
						$result = $db->q("SELECT * FROM bumpQueue ORDER BY timeToSend");
						foreach($result as $key=>$r) : ?>
						<li class="schmancy">
						<a class="removeReminder" style="cursor:pointer;" id="bump-<?php echo $r['bumpId']; ?>" title="Remove this reminder">
						<img src="layout/images/delbtn.png" style="float:right;" /></a>
						<?php echo 'To:'.$r['fromEmail'].' <br />Subject: '.$r['subject'].' <br />Time: '.date('m/d/Y h:iA', $r['timeToSend']);	?>
						</li>
						<?php endforeach; ?>
					</ul>
			 </div>
		   </div>

              <div id="footer">
		<p><a href="http://bumper.cc" title="Bumper">Bumper!</a></p>
		<!-- You can do whatever you like here -->
	      </div>
	      
	    </div>
	  </div>
	</div>

      </div>
    </div>

  </body>

</html>
<?php } ?>