<?php
include("../includes/class.login.php");
$log = new logmein();
$log->encrypt = true; //set encryption
//parameters are(SESSION, name of the table, name of the password field, name of the username field)
if($log->logincheck($_SESSION['loggedin'], "logon", "password", "useremail") == false){
    die();
}else{

require_once('../includes/dbWrapper.php');
require_once('../includes/bumper.config');
error_reporting(0);
$db = new dbWrapper('localhost', DBUSER, DBPASS, DBNAME, true);//************************************  add new allowed email to db and set default timezone and email format
if ($_POST['action'] == 'addAllowedEmail') : 
$result = $db->q("SELECT * FROM allowedEmails WHERE allowedEmail = ?", 's', $_POST['newEmail']);
if (is_array($result) and count($result) > 0) { echo 'That email address is already on the allowed list.'; die(); }
$result = $db->q("INSERT INTO allowedEmails (allowedEmail, timeZone, emailFormat) VALUES ( ?, ?, ? )", 'sss', $_POST['newEmail'], 'America/Anguilla', 'html');
						$result = $db->q("SELECT * FROM allowedEmails");
						foreach($result as $key=>$email) : ?>
						<li class="schmancy"><?php echo $email['allowedEmail']; ?>
						<a class="removeEmail" style="cursor:pointer;" id="<?php echo $email['allowedEmail']; ?>" title="Remove this email">
						<img src="layout/images/delbtn.png" style="float:right;" /></a></li>
						<?php endforeach;
die();
endif;

if ($_POST['action'] == 'removeAllowedEmail') : //************************************  remove deleted email from allowedEmail table in db
$result = $db->q("DELETE FROM allowedEmails WHERE allowedEmail = ?", 's', $_POST['removeEmail']);
die();
endif;

if ($_POST['action'] == 'setEmailTimeZone') : //************************************  update timezone for selected email
$result = $db->q("UPDATE allowedEmails SET timeZone = ? WHERE allowedEmail = ?", 'ss', $_POST['timeZone'], $_POST['allowedEmail']);
die();
endif;

if ($_POST['action'] == 'setEmailFormat') : //************************************  update email format for selected email
$result = $db->q("UPDATE allowedEmails SET emailFormat = ? WHERE allowedEmail = ?", 'ss', $_POST['format'], $_POST['allowedEmail']);
die();
endif;
}