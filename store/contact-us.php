<?php
include_once("classes/config.php");
$Content			= $Web->getRecord('contact-us', 'UrlKeyword', 'content');
$PageTitle			= $Content['Title'];
$pageDescription	= $Web->FilterDescription($Content['Content']);

$MetaTitle = $PageTitle;
include_once("html_header.php");
include_once("header.php");
?>
<section class="main-content inner-page contact-page no-margin">
<div class="clear height5"></div>
    <div class="container">
    <div class="heading"><h2><?php echo $PageTitle; ?></h2></div>
    <div class="fleft contact-form">
    	<p>Please send us your questions, comments or concerns by filling out the form below. We´ll do our best to get back to you as quickly as possible, usually within 1-2 business days.</p>
    	<form name="ContactForm" id="ContactForm" method="post" action="">
        	<input type="hidden" name="ContactFormFlag" id="ContactFormFlag" />
            <input name="Name" id="Name" type="text" class="TextField" required="required" placeholder="Enter your name..." />
            <input name="Email" id="Email" type="email" class="TextField" required="required" placeholder="Enter your email..." />
            <input name="Phone" id="Phone" type="text" class="TextField" required="required" placeholder="Enter your phone number..." />
            <!--<select required="required" class="TextField" >
								<option value="Search Engine">Search Engine</option>
								<option value="Flyer">Flyer</option>
								<option value="Advert">Advert</option>
								<option value="Word of mouth">Word of mouth</option>
								<option value="Social Media">Social Media</option>
								<option value="Other">Other</option>
							</select>-->
            <textarea name="Message" id="Message" class="Textarea" required="TextField" placeholder="Enter your message..."></textarea>
            <input type="submit" value="Submit" class="Button" />&nbsp;<span id="ErrorMsg"></span>
        </form>
    </div>
    <div class="fleft contact-details">
    	<div class="content-div">
            <div class="CmsDescription"><?php echo $pageDescription; ?></div>
        </div>
    </div>
    <div class="clear"></div>
    </div>
</section>
<?php
	include_once("subscription.php");
	include_once("footer.php");
	include_once("html_footer.php");
?>