<?php
include_once("classes/config.php");
if(isset($_SESSION[WEB_USER_SESSION]))
	$Web->Redirect(WEB_URL);
	
$MetaTitle = 'Login';
include_once("html_header.php");
include_once("header.php");
?>
<section class="main-content inner-page contact-page no-margin">
<div class="clear height5"></div>
    <div class="container">
    <div class="heading"><h2>Login</h2></div>
    <div class="fleft contact-form">
    	<form name="LoginForm" id="LoginForm" method="post" action="">
        	<input type="hidden" name="LoginFormFlag" id="LoginFormFlag" />
            <input type="text" name="LoginEmail" id="LoginEmail" class="TextField" required="required" placeholder="Enter your email address..." />
            <input type="password" name="LoginPassword" id="LoginPassword" class="TextField" required="required" placeholder="Enter your password..." />
            <input type="submit" value="Submit" class="Button" />&nbsp;<span id="LoginErrorMsg"></span>
        </form>
    </div>
    <div class="fleft contact-details login-register-msg">
    	<h2>Don't have an account? <a href="<?php echo WEB_URL; ?>register.html">Click here to register</a></h2>
        <!--<br />or login with facebook<br />
        <fb:login-button scope="public_profile,email" onlogin="checkLoginState();" auto_logout_link="false"></fb:login-button>
        <div id="fbStatus"></div>-->
    </div>
    <div class="clear"></div>
    </div>
</section>
<?php
	include_once("subscription.php");
	include_once("footer.php");
	include_once("html_footer.php");
?>