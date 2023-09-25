<?php
include_once("classes/config.php");
if(isset($_SESSION[WEB_USER_SESSION]))
	$Web->Redirect(WEB_URL);
	
$MetaTitle = 'Register';
include_once("html_header.php");
include_once("header.php");
?>
<section class="main-content inner-page contact-page no-margin">
<div class="clear height5"></div>
    <div class="container">
    <div class="heading"><h2>Register</h2></div>
    <div class="fleft contact-form">
    	<form name="RegisterForm" id="RegisterForm" method="post" action="">
        	<input type="hidden" name="RegisterFormFlag" id="RegisterFormFlag" />
            <input type="hidden" name="EmailError" id="EmailError" />
            <input type="text" name="Name" id="Name" class="TextField" required="required" placeholder="Enter your name..." />
            <input type="email" name="RegisterEmail" id="RegisterEmail" class="TextField" required="required" onBlur="ValidateEmail(this.value);" placeholder="Enter your email address..." />
            <input type="text" name="Mobile" id="Mobile" class="TextField" required="required" placeholder="Enter your mobile number..." />
            <input type="password" name="RegisterPassword" id="RegisterPassword" class="TextField" required="required" placeholder="Enter your password..." />
            <input type="password" name="ConfirmPassword" id="ConfirmPassword" class="TextField" required="required" placeholder="Enter password again..." />
            <input type="submit" value="Submit" class="Button" />&nbsp;<span id="RegisterErrorMsg"></span>
        </form>
    </div>
    <div class="fleft contact-details">
    	<h2>Already have an account? <a href="<?php echo WEB_URL; ?>login.html">Click here to login</a></h2>
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