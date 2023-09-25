<?php
include_once("classes/config.php");

unset($_SESSION[USER_CART]);
unset($_SESSION['DoPayment']);

if(isset($_SESSION[WEB_MSG]))
{
	$MetaTitle = $_SESSION[WEB_MSG]['Title'];
	include_once("html_header.php");
	include_once("header.php");
?>
<section class="main-content inner-page no-margin">
	<div class="clear height5"></div>
    <div class="container">
        <div class="content-div">
            <div class="heading"><h2><?php echo $_SESSION[WEB_MSG]['Title']; ?></h2></div>
            <div class="CmsDescription"><p><?php echo $_SESSION[WEB_MSG]['Desc']; ?></p></div>
        </div>
    </div>
</section>
<?php
	include_once("subscription.php");
	include_once("footer.php");
	include_once("html_footer.php");
}
else
	$Web->Redirect(WEB_URL);
?>