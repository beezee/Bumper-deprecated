<?php
$url = (!empty($_SERVER['HTTPS'])) ? 'https://'.$_SERVER['SERVER_NAME'] : 'http://'.$_SERVER['SERVER_NAME'];
if ($_SERVER['SERVER_NAME'] != basename(dirname(dirname(__FILE__)))) $url .= '/'. basename(dirname(dirname(__FILE__)));
?>
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
    
    <title>Bumper Login</title>
    
    <style type="text/css" title="Bumper" media="all">
      @import "../layout/simplicity-two-oh-three.css";
    </style>
    <!--[if IE]>
    <style type="text/css" media="all">
      @import "layout/ie-diff.css";
    </style> 
    <![endif]-->
	<link href="../css/style.css" media="screen" rel="stylesheet" type="text/css"/>
    <link href="../css/uniform.css" media="screen" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="../layout/print.css" media="print" />
	<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/themes/flick/jquery-ui.css" media="screen" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script> 
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.js"></script> 
	<script type="text/javascript">
		$(document).ready(function() {
			$('#loginnow').click(function() {
				var data = {
					action : 'login',
					username : $('#username').val(),
					password : $('#password').val()
					}
				$.post( 'login.php', data, function(response) {
						if (response != 'Success!') {
							$( "#formerror" ).dialog({
							height: 160,
							modal: true
							});
						return; 
						} else {
						window.location = '<?php echo $url; ?>';
						}
				});
			});
			
			$('#resetpw').click(function() {
					$('#theloginform').fadeOut('slow', function() {
						$('#theresetform').fadeIn('slow');
					});
					
					$('#resetnow').click(function() {
								var data = {
							action : 'reset',
							username : $('#resetusername').val(),
							}
						$.post( 'login.php', data, function(response) {
								if (response != 'Success!') {
									$( "#formerror" ).dialog({
									height: 160,
									modal: true
									});
								return; 
								} else {
								$( "#resetsuccess" ).dialog({
									height: 160,
									modal: true
								}); }
						});
					});
				});
		});
	</script>
    <link rel="shortcut icon" href="http://wherever-your-favicon-is.org/favicon.png"  />
    <link rel="icon" href="http://wherever-your-favicon-is.org/favicon.png" />
    
  </head>
  
  <body>
    
    <div id="window">
      <div id="container">
	
	
	
	<div id="main">
	  <div id="outer-prettification">
	    <div id="inner-prettification">
	      
	      <div id="header">
		<h1 id="title"><span>Welcome to Bumper, please login.</span></h1>
	      </div>
		  
		  <div id="formerror" style="display:none;">
			<div class="ui-state-error" id="formerrortext" style="text-align:center;font-size:18px;margin:30px auto 0;">
			Invalid login, please try again.
			</div>
		</div>
	      <div id="resetsuccess" style="display:none;">
			<div class="ui-state-error" id="resetsuccesstext" style="text-align:center;font-size:18px;margin:30px auto 0;">
			Check your email!
			</div>
		</div>
	      <div id="contents" style="padding-top:90px;">
				<form id="theloginform" class="TTWForm" method="post" novalidate="">
           
           
          <div id="field10-container" class="field f_100">
               <label for="field10">
                    Admin Email
               </label>
               <input type="text" id="username" required="required">
          </div>
           
           
          <div id="field11-container" class="field f_100">
               <label for="field11">
                    Admin Password
               </label>
               <input type="password" id="password" required="required">
          </div>
           
           
          <div id="form-submit" class="field f_100 clearfix submit">
			   <input type="button" style="float:left;" id="resetpw" value="Forgot Password?" />
               <input type="button" id="loginnow" value="Login" />
          </div>
     </form>
				<form id="theresetform" style="display:none;" class="TTWForm" method="post" novalidate="">
           
           
					  <div id="field10-container" class="field f_100">
						   <label for="field10">
								Admin Email
						   </label>
						   <input type="text" id="resetusername" required="required">
					  </div>
					  
					   
					   
					  <div id="form-submit" class="field f_100 clearfix submit">
						   <input type="button" id="resetnow" value="Reset Password" />
					  </div>
				 </form>
	 
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