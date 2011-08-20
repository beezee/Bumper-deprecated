<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title></title>
    <link href="../css/style.css" media="screen" rel="stylesheet" type="text/css"/>
    <link href="../css/uniform.css" media="screen" rel="stylesheet" type="text/css"/>
	<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/themes/flick/jquery-ui.css" media="screen" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script> 
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.js"></script> 
	<script type="text/javascript">
		function validateEmail(email) 
				{ 
				 var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/ 
				 return email.match(re) 
				}
		$(document).ready(function() {
			$('#configdb').click(function () {
				if ( $('#dbname').val() == '' || $('#dbuser').val() == '' || $('#dbpassword').val() == '') 
					{ 
					$('#formerrortext').text('All fields are required.');
					$( "#formerror" ).dialog({
							height: 140,
							modal: true
						});
					return; 
					}
					var data = {
						action : 'checkdb',
						dbname : $('#dbname').val(),
						dbuser : $('#dbuser').val(),
						dbpassword : $('#dbpassword').val(),
						};
				$.post('includes/dbconfig.php', data, function(response) {
					if (response != 'Success!') {
							if (response.substr(0, 22) == 'Access denied for user') {
								var errmsg = 'Couldn\'t connect to the database. Please check your credentials and try again.';
								} else {
								var errmsg = response;
								}
						$('#formerrortext').text(errmsg);
						$( "#formerror" ).dialog({
							height: 160,
							modal: true
							});
						return; 
						}
					$('#dbform').fadeOut('slow', function() {
								$('#adminuserform').fadeIn('slow');
					});
				});
			});
			
			$('#createadmin').click(function() {
					if ( $('#adminemail').val() == '' || $('#adminpassword').val() == '') 
					{ 
					$('#formerrortext').text('All fields are required.');
					$( "#formerror" ).dialog({
							height: 140,
							modal: true
						});
					return; 
					}
					var validemail = validateEmail($('#adminemail').val());
					if (validemail == null) {
					$('#formerrortext').text('Please use a valid email address');
					$( "#formerror" ).dialog({
							height: 140,
							modal: true
						});
					return; 
					}
					var data = {
						action : 'finishsetup',
						dbname : $('#dbname').val(),
						dbuser : $('#dbuser').val(),
						dbpassword : $('#dbpassword').val(),
						adminemail : $('#adminemail').val(),
						adminpassword : $('#adminpassword').val(),
						}
					$.post('includes/dbconfig.php', data, function(response) {
							if (response != 'Success!') {
									if (response.substr(0, 22) == 'Access denied for user') {
											var errmsg = 'Couldn\'t connect to the database. Please check your credentials and try again.';
											} else {
											var errmsg = response;
											}
									$('#formerrortext').text(errmsg);
									$( "#formerror" ).dialog({
										height: 160,
										modal: true
										});
									return; 
									} else {
								$('#adminuserform').fadeOut('slow', function() {
								$('#successful').fadeIn('slow');
							});
							}
					});
			});
		});
	</script>
	<style type="text/css" title="The shiny, Web 2.0 version of 'Simplicity,' a pseudo-professional style-sheet." media="all">
      @import "../layout/simplicity-two-oh-three.css";
    </style>
    <!--[if IE]>
    <style type="text/css" media="all">
      @import "layout/ie-diff.css";
    </style> 
    <![endif]-->

    <link rel="stylesheet" type="text/css" href="../layout/print.css" media="print" />
    <link rel="shortcut icon" href="http://wherever-your-favicon-is.org/favicon.png"  />
    <link rel="icon" href="http://wherever-your-favicon-is.org/favicon.png" />
</head>
<body>
<div id="main">
	  <div id="outer-prettification">
	    <div id="inner-prettification">
	      
	      <div id="header">
		<h1 id="title"><span>Welcome to Bumper!</span></h1>
	      </div>
		  
		  <div id="formerror" style="display:none;">
			<div class="ui-state-error" id="formerrortext" style="text-align:center;font-size:18px;margin:30px auto 0;">
			All fields are required.
			</div>
		  </div>
	      
	      <div id="contents">	
    <?php if (!file_exists('../includes/bumper.config')) : ?>
	 <div id="dbform">
	 
		 <div class="fancy">
		  <p style="width:76%;margin:0 auto;">To begin you'll need an empty MySQL Database on your host.
		  <br /><br />Fill out the form below to create Bumper tables in the Database.
		  </p>
		  </div>
		  
		  
		<form class="TTWForm" method="post" novalidate="">
          
          
          <div id="field2-container" class="field f_100">
               <label for="field2">
                    Database Name
               </label>
               <input type="text" id="dbname" required="required">
          </div>
          
          
          <div id="field3-container" class="field f_100">
               <label for="field3">
                    Database User
               </label>
               <input type="text" id="dbuser" required="required">
          </div>
          
          
          <div id="field5-container" class="field f_100">
               <label for="field5">
                    Database Password
               </label>
               <input type="password" id="dbpassword" required="required">
          </div>
          
          
          <div id="form-submit" class="field f_100 clearfix submit">
               <input type="button" id="configdb" value="Configure Database">
          </div>
     </form>
	</div>
	<div id="adminuserform" style="display:none;">
			
			<div class="fancy">
		  <p style="width:76%;margin:0 auto;">Choose an email address for your admin username and create a password.
		  <br /><br />Bumper will use this email in case you lose your password.
		  </p>
		  </div>
	
			<form class="TTWForm" method="post" novalidate="">
           
           
          <div id="field10-container" class="field f_100">
               <label for="field10">
                    Admin Email
               </label>
               <input type="text" id="adminemail" required="required">
          </div>
           
           
          <div id="field11-container" class="field f_100">
               <label for="field11">
                    Admin Password
               </label>
               <input type="password" id="adminpassword" required="required">
          </div>
           
           
          <div id="form-submit" class="field f_100 clearfix submit">
               <input type="button" id="createadmin" value="Create Admin Account and Finish Setup!">
          </div>
     </form>
	</div>
	<div id="successful" style="display:none;">
			
			<div class="fancy">
		  <p style="width:76%;margin:0 auto;">Congratulations, you're all set up!
		  </p>
		  </div>
		  <div class="fancy">
		  <p style="width:76%;margin:0 auto;">
			<span style="font-style:italic;">Important!</span><br /><br />
			Be sure to delete the setup folder from the Bumper installation folder in order to secure your server.<br /><br />
			You can login to manage Bumper at <a href="<?php if (!empty($_SERVER['HTTPS'])) { echo 'https://'; } else { echo 'http://';} echo $_SERVER['SERVER_NAME']; if ($_SERVER['SERVER_NAME'] != basename(dirname(dirname(__FILE__)))) echo '/'. basename(dirname(dirname(__FILE__))); ?>">
			<?php echo $_SERVER['SERVER_NAME']; if ($_SERVER['SERVER_NAME'] != basename(dirname(dirname(__FILE__)))) echo '/'. basename(dirname(dirname(__FILE__))); ?></a>.
		  </p>
		  </div>
	</div>
			<?php else : ?>
				
				<div class="fancy">
		  <p style="width:76%;margin:0 auto;font-size:22px;color:blue;text-align:left;">
		  <span style="font-weight:bold;font-style:italic;">WARNING!</span><br /><br />
		  You still have your Bumper setup folder on your server, which means anyone can wipe out your Bumper installation from a web browser.<br /><br />
		  If you want to reinstall, please delete the "bumper.config" file from the BUMPER_INSTALLATION_FOLDER/includes directory and reload this page, otherwise 
		  please remove the BUMPER_INSTALLATION_FOLDER/setup directory to secure your server.
		  </p>
		  </div>
			<?php endif; ?>
</div>

              <div id="footer">
		<p><a href="http://www.bumper.cc" title="Bumper Homepage">Bumper!</a></p>
		<!-- You can do whatever you like here -->
	      </div>
	      
	    </div>
	  </div>
	</div>

      </div>
    </div>

</body>
</html>