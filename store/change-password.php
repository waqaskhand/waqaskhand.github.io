<?php
include_once("classes/config.php");
if(!isset($_SESSION[WEB_USER_SESSION]))
	$Web->Redirect(WEB_URL);
	
$MetaTitle = 'Change Password';
include_once("html_header.php");
include_once("header.php");
?>
<section class="main-content inner-page contact-page">
    <div class="container">
    <div class="heading"><h2>Change Password</h2></div>
    <div class="fleft contact-form">
    	<form name="ChangePasswordForm" id="ChangePasswordForm" method="post" action="">
        <input type="hidden" name="ChangePasswordFlag" id="ChangePasswordFlag" />
        	<input type="password" name="OldPassword" id="OldPassword" class="TextField" required="required" placeholder="Enter old password..." />
            <input type="password" name="NewPassword" id="NewPassword" class="TextField" required="required" placeholder="Enter new password..." />
            <input type="password" name="ConfirmPassword" id="ConfirmPassword" required="required" class="TextField" placeholder="Enter new password again..." />
            <input type="submit" value="Submit" class="Button" />&nbsp;<span id="ChangePasswordErrorMsg"></span>
        </form>
    </div>
    <div class="fleft contact-details login-register-msg">
    	
    </div>
    <div class="clear"></div>
    </div>
</section>
<?php
	include_once("subscription.php");
	include_once("footer.php");
	include_once("html_footer.php");
?>