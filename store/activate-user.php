<?php
include_once("classes/config.php");
if(isset($_REQUEST['user']))
{
	$UserID = $Web->convertString($_REQUEST['user'], HANDLER, 'D');
	$UserID = str_replace("GW-", "", $UserID);
	$UserID = str_replace("-GW", "", $UserID);
	
	$Web->query("select * from web_users where TableID='$UserID'");
	
	if($Web->num_rows()>0)
	{
		$Web->query("update web_users set Status='".ACTIVE."' where TableID='$UserID'");
		
		$_SESSION[WEB_USER_SESSION] = $Web->getRecord($UserID, "TableID", "web_users");
		
		$_SESSION[WEB_MSG]['Title']='Account activated sucessfully.';
		$_SESSION[WEB_MSG]['Desc']='Thank you '.$_SESSION[WEB_USER_SESSION]["Name"].' for signing up on LP Air Con. Now you can continue shopping.';
		
		if(isset($_SESSION['BacktoCheckout']))
			$Web->Redirect(WEB_URL."checkout.html");
		else
			$Web->Redirect(WEB_URL."thank-you.html");
	}
}
$Web->Redirect(WEB_URL);
?>