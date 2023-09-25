<?php
include_once("classes/config.php");
if(isset($_REQUEST['Keyword']))
{
	
	$Content			= $Web->getRecord($_REQUEST['Keyword'], 'UrlKeyword', 'content');
	$MetaTitle			= $Content['Title'];
	$pageDescription	= $Web->FilterDescription($Content['Content']);
	include_once("html_header.php");
	include_once("header.php");
?>
<section class="main-content inner-page no-margin">
	<div class="clear height5"></div>
    <div class="container">
        <div class="content-div">
            <div class="heading"><h2><?php echo $MetaTitle; ?></h2></div>
            <div class="CmsDescription"><?php echo $pageDescription; ?></div>
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