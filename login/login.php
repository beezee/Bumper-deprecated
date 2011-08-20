<?php
//instantiate if needed
include("../includes/class.login.php");
$log = new logmein();
$log->encrypt = true; //set encryption
if($_REQUEST['action'] == "login"){
    if($log->login("logon", $_REQUEST['username'], $_REQUEST['password']) == true){
        echo 'Success!';
		die();
    }else{
       echo 'Try again';
	   die();
    }
	die();
}
if($_REQUEST['action'] == 'logout') : 
//Log out
$log->logout();
die();
endif;
if($_REQUEST['action'] == 'reset') : 
    if($log->passwordreset($_REQUEST['username'], "logon", "password", "useremail") == true){
        //do something on successful password reset
		echo 'Success!';
    }else{
        echo 'noSucces:(';
    }
endif; 
die();