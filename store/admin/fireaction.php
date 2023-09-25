<?php
if(isset($_REQUEST['refreshAjax']))
{
	include_once("../classes/config.php");
	$_REQUEST = $Web->FilterString($_REQUEST);
	extract($_REQUEST);
}

if($_REQUEST['Trigger']==ADMIN_LOGIN_TRIGGER)
{
	$Password = md5($Password);
	$SystemAdmin = new SystemAdmin();
	$Login = $SystemAdmin->Login($Username, $Password);
	if($Login["Error"]==1)
	{
		$_SESSION[CONFIRMATION_MESSAGE] = $Login["Msg"];
	}
}

else if($_REQUEST['ChangePasswordFlag']=='true')
{
	$SystemAdmin = new SystemAdmin();
	$SystemAdmin->ChangePassword($_REQUEST);
	$Web->Redirect('index.php?page=change-password');
}

else if($_REQUEST['ValidateContentFlag']=='true')
{
	$WebContent = new content();
	$WebContent->ManageContent($_REQUEST);
	$Web->Redirect(ADMIN_URL."index.php?page=content");
}

else if($_REQUEST['ValidateBrandFlag']=='true')
{
	$Products = new products();
	$Return = $Products->ManageBrand($_REQUEST);
	$Web->Redirect(ADMIN_URL."index.php?page=product-brands");
}

else if($_REQUEST['ValidateCategoryFlag']=='true')
{
	$Products = new products();
	$Return = $Products->ManageCategory($_REQUEST);
	$Web->Redirect(ADMIN_URL."index.php?page=".$returnPage);
}

else if($_REQUEST['ValidateColorFlag']=='true')
{
	$Products = new products();
	$Return = $Products->ManageColor($_REQUEST);
	$Web->Redirect(ADMIN_URL."index.php?page=product-colors");
}

else if($_REQUEST['ValidateSizeFlag']=='true')
{
	$Products = new products();
	$Return = $Products->ManageSize($_REQUEST);
	$Web->Redirect(ADMIN_URL."index.php?page=product-size");
}

else if($_REQUEST['ValidateProductFlag']=='true')
{
	$Products = new products();
	$Return = $Products->ManageProduct($_REQUEST);
	$Web->Redirect(ADMIN_URL."index.php?page=products");
}

else if($_REQUEST['UpdateStatusFlag']=='true')
{
	$Products = new products();
	$Return = $Products->UpdateStatus($_REQUEST);
}
else if($_REQUEST['DeleteImage']=='true')
{
	$WebContent = new content();
	$WebContent->DeleteRecordImage($_REQUEST);
}
else if($_REQUEST['DeleteOrderFlag']=='true')
{
	$Products = new products();
	$Return = $Products->DeleteOrder($RecordID);
}
else if($_REQUEST['UpdateSequenceFlag']=='true')
{
	$WebContent = new content();
	$WebContent->UpdateSequence($_REQUEST);
	echo '1';
	//$Web->Redirect(ADMIN_URL."index.php?page=".$returnPage."&SequenceUpdated=1");
}
else if($_REQUEST['ValidateWebUserFlag']=='true')
{
	$WebContent = new content();
	$WebContent->DeleteWebUser($_REQUEST);
}
else if($_REQUEST['ValidateSettingsFlag']=='true')
{
	$WebContent = new content();
	$WebContent->ManageSettings($_REQUEST);
	$Web->Redirect(ADMIN_URL."index.php?page=settings");
}
else if($_REQUEST['ValidateBannerFlag']=='true')
{
	$WebContent = new content();
	$WebContent->ManageBanner($_REQUEST);
	$Web->Redirect(ADMIN_URL."index.php?page=banners");
}
else if($_REQUEST['ValidateDCodeFlag']=='true')
{
	$Products = new products();
	$Return = $Products->GenerateCodes($_REQUEST);
	$Web->Redirect(ADMIN_URL."index.php?page=view-code");
}
else if($_REQUEST['ValidateSubscriberFlag']=='true')
{
	$WebContent = new content();
	$WebContent->DeleteSubscriber($_REQUEST);
}
?>