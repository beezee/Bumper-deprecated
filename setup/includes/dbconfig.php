<?php
require_once('../../includes/dbWrapper.php');
error_reporting(0);

if ($_POST['action'] == 'checkdb') : 
//************************************  check to make sure we can connect to db and that it is empty
$db = new dbWrapper('localhost', $_POST['dbuser'], $_POST['dbpassword'], $_POST['dbname'], true);
if (!$db or $db == '' or empty($db)) {
echo "Couldn't connect to the database. Please check your credentials and try again.";
die();
}
$checkempty = $db->q('SHOW tables');
if ($checkempty and $checkempty != '' and is_array($checkempty) and !empty($checkempty)) {
echo 'This database already has tables. Bumper needs an empty database.';
die();
}
echo 'Success!';
die();
endif;

if ($_POST['action'] == 'finishsetup') :
//************************************  create tables
$db = new dbWrapper('localhost', $_POST['dbuser'], $_POST['dbpassword'], $_POST['dbname'], true);
$result = $db->q("CREATE TABLE logon (
  userId int(11) NOT NULL auto_increment PRIMARY KEY,
  userEmail varchar(50) NOT NULL default '',
  password varchar(50) NOT NULL default '',
  userLevel int(1) NOT NULL default '0'
) TYPE=MyISAM");
$result = $db->q("CREATE TABLE allowedEmails (
  emailId int(11) NOT NULL auto_increment PRIMARY KEY,
  allowedEmail varchar(50) NOT NULL default '',
  timeZone varchar(90) NOT NULL default '',
  emailFormat varchar(30) NOT NULL default ''
) TYPE=MyISAM");
$result = $db->q("CREATE TABLE bumpQueue (
  bumpId int(11) NOT NULL auto_increment PRIMARY KEY,
  fromEmail varchar(50) NOT NULL default '', 
  subject varchar(50),
  body text,
  timeToSend int(11)  
) TYPE=MyISAM");
$result = $db->q("CREATE INDEX timeToSend ON bumpQueue (timeToSend) USING BTREE");
//************************************  create config file secure it, and make sure that inbox.php is accessible
$fp = fopen('../../includes/bumper.config', 'w');
fwrite($fp, '<?php
define("DBNAME", "'.$_POST['dbname'].'");
define("DBUSER", "'.$_POST['dbuser'].'");
define("DBPASS", "'.$_POST['dbpassword'].'");
');
fclose($fp);
chmod("../../includes/bumper.config", 0600);
chmod("../../inbox.php", 0755);
//************************************  create admin account and add admin email to allowed emails
$result = $db->q("INSERT INTO logon (userEmail, password) VALUES ( ?, MD5(?) )", 'ss', $_POST['adminemail'], $_POST['adminpassword']);
$result = $db->q("INSERT INTO allowedEmails (allowedEmail, timeZone, emailFormat) VALUES ( ?, ?, ? )", 'sss', $_POST['adminemail'], 'America/Anguilla', 'html');
echo 'Success!';
die();
endif;