<?php
session_start();
error_reporting(1);
define("ROOT_PATH", dirname(dirname(__FILE__))."/");


	define("WEB_URL", "http://localhost/store/");
	define("ADMIN_URL", WEB_URL."admin/");
	define("DATABASE_NAME","lpaircon");
	define("DATABASE_USER","root");
	define("DATABASE_PASSWORD","");
	define("DATABASE_HOST","localhost");

define("JS_PATH", WEB_URL."js/");



define("CSS_PATH", WEB_URL."css/");



define("IMAGES_PATH", WEB_URL."images/");



define("CLASS_PATH", ROOT_PATH."classes/");



define("ADMIN_LOGGED_IN", "LPAirConAdminLoginSession");



define("CONFIRMATION_MESSAGE", "ConfirmationMessage");



define("WEBSITE_SETTINGS", "LPAirConWebsiteSetting");



define("USER_CART", "LPAirConUserCart");



define("WEB_USER_SESSION", "LPAirConUserLoggedIn");



define("WEB_MSG", "LPAirConConfirmationMessage");



define("PRODUCTS_QUERY", "LPAirConAdminProductsQuery");



define("ORDERS_QUERY", "LPAirConAdminOrdersQuery");



define("USER_PRODUCTS_QUERY", "LPAirConProductSearchQuery");







define("HANDLER","@##@##Asdofpokg@##@##2340o8092384098sdfkljghskdlfhgkhfdglksdfhgkljsdfh@##@##@##20-9@@@@2##%##5op%^&*&((*&rtrt()##dsfkju#34905804");



define("NO_TIMES",3);



define('PREDEFINED_SALT_VALUE', 'F/||&@USGH#U|*Q');







define("ACTIVE", 1);



define("INACTIVE", 0);







$ActiveInactive[1]='Yes';



$ActiveInactive[0]='No';







define("DTYPE_MIN", "1");



define("DTYPE_HOUR", "2");







$DType[1]='min';



$DType[2]='hour(s)';







define("G_MALE", "1");



define("G_FEMALE", "0");



$Gender[0]='Female';



$Gender[1]='Male';







$GenderMF[0]='Women';



$GenderMF[1]='Men';







// Triggers



define("ADMIN_LOGIN_TRIGGER", "AdminLogin");



define("ADD_RECORD_TRIGGER", "AddRecord");



define("EDIT_RECORD_TRIGGER", "EditRecord");



define("VALIDATE_TRIGGER", "Validate");



define("DELETE_RECORD_TRIGGER", "DeleteRecord");



define("UPDATE_SEQUENCE_TRIGGER", "UpdateSequence");



define("UPDATE_SYSTEMUSER_PERMISSION", "UpdatePermssion");







define("ZERO_TO_ADD", "4");



define("IMAGES_FOLDER", "web_images");



define("ORIGINAL_IMAGES", "original_images");



define("SONG_FOLDER", "songs");







define("IMAGE_TYPE_NORMAL", "0");



define("IMAGE_TYPE_BANNER", "1");



define("IMAGE_TYPE_PRODUCT_GALLERY", "2");



define("IMAGE_TYPE_SERVICE_GALLERY", "3");







// -1 pixel of all images width and height



define("BANNER_WIDTH", 1366);



define("BANNER_HEIGHT", 400);







define("PRODUCT_HOME_IMAGE_WIDTH", "212");



define("PRODUCT_HOME_IMAGE_HEIGHT", "212");







define("PRODUCT_IMAGE_GALLERY_WIDTH", "800");



define("PRODUCT_IMAGE_GALLERY_HEIGHT", "600");







define("BRAND_LOGO_IMAGE_WIDTH", "198");



define("BRAND_LOGO_IMAGE_HEIGHT", "100");







define("DEFAULT_CURRENCY", "Rs.");







define("VERSION", 3);







define("EMAIL_NOTIFICATION_FOOTER", "Web Administrator");



define("SYSTEM_EMAIL_FROM_NAME", "No-reply");



define("SYSTEM_EMAIL_FROM_EMAIL", "no-reply@lpaircon.co.uk");









$ProductType[1]='Normal';



$ProductType[2]='Promotion';



$ProductType[3]='Wholesale';







$PaymentMode[1]='Pay Online';



//$PaymentMode[2]='Cash on Delivery';



//$PaymentMode[3]='Pickup from our warehouses';







$OrderStatus[1]='Under Process';



$OrderStatus[2]='Sent for Delivery';



$OrderStatus[3]='Delivered';



$OrderStatus[4]='Cancelled';







$SongExtArray[1]='mp3';







define("PRODUCT_ITEM_PER_GROUP", "15");







$BannerTypes[2]='Small  - Left Side';



$BannerTypes[1]='Large - Right Side';



$BannerSizes[1]['Width']='797';



$BannerSizes[1]['Height']='440';



$BannerSizes[2]['Width']='270';



$BannerSizes[2]['Height']='219';







define("ONE_MB", "1048576");



define("SMB" , "10");



$HMB = SMB * ONE_MB;



define("SONG_SIZE", $HMB);


/*
function __autoload($class_name='')
{
	if($class_name!='')
		include_once(CLASS_PATH.$class_name.'.php');
}*/

spl_autoload_register(function($class_name) {
	include_once(CLASS_PATH.$class_name.'.php');
});






$Web = new WebFunctions();



$Web2 = new WebFunctions();



$Web3 = new WebFunctions();



$Products = new products();







$mail = new PHPMailer();



$mail->isSMTP();



$mail->SMTPDebug = 0;



$mail->Debugoutput = 'html';



$mail->Host = "mail.lpaircon.co.uk";



$mail->Port = 25;



$mail->SMTPAuth = true;



$mail->Username = "sendmail@lpaircon.co.uk";



$mail->Password = "&4RjWpe3.LXc";







if(!isset($_SESSION[WEBSITE_SETTINGS]))
{

	$Web->query("select * from settings");
	
	if($Web->num_rows()>0)
	{
		while($Web->next_Record())
		{
			$TableID = $Web->f('TableID');
			$Data = $Web->f('Data');
			$_SESSION[WEBSITE_SETTINGS][$TableID] = $Data;
		}
	}
}


define("EMAIL_ADDRESS_FOR_INQUIRY", $_SESSION[WEBSITE_SETTINGS][2]);



define("PAYPAL_ID", $_SESSION[WEBSITE_SETTINGS][7]);



$_SESSION['Currency'] = DEFAULT_CURRENCY;











$mainCatClass[1]='cakes';



$mainCatClass[2]='flowers';



$mainCatClass[3]='balloons';



/*$mainCatClass[4]='chocolates';



$mainCatClass[5]='for-him';



$mainCatClass[6]='for-her';



$mainCatClass[7]='kids';*/



?>