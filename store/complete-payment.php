<?php
error_reporting(0);
session_start();
if(isset($_REQUEST['Token']) && $_REQUEST['Token']!='')
{
	$DoPayment = $_SESSION['DoPayment'];
	$Token = $_REQUEST['Token'];
	require_once 'white/White/Charge.php';
	require_once 'white/White/Customer.php';
	require_once 'white/White/Error.php';
	require_once 'white/White/Error/Authentication.php';
	require_once 'white/White/Error/Card.php';
	require_once 'white/White/Error/Api.php';
	require_once 'white/White/Error/Parameters.php';
	require_once 'white/White.php';

	White::setApiKey("sk_test_1234567890abcdefghijklmnopq");
	
	$Return = White_Charge::create(array("amount"=>$DoPayment['AmountToPay'], "currency"=>"AED", "card"=>$Token, "description"=>$DoPayment['Description']));
	$Json['Error'] = 0;
	if(isset($Return['error']))
	{
		$Json['ReturnUrl']	= '';
		$Json['Error']		= 1;
		$Error				= $Return['error'];
		$Json['Msg']		= $Error['message'];
		
		if($Error['code']=='processing_error')
			$Json['Msg'] .= ' Please try again.';
	}
	else
	{
		$Json['Msg'] = 'Payment received! Thank you.';
		$Json['ReturnUrl'] = $DoPayment['ReturnURL'];
	}
	echo json_encode($Json);
}
?>