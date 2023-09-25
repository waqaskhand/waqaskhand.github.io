<?php
include_once("classes/config.php");
$CurrentDate=date("Y-m-d");

if(isset($_REQUEST['SubscriptionFormFlag']) && $_REQUEST['SubscriptionFormFlag']=='true')
{
	$Web->query("select * from subscriptions where Email='$SubsEmail'");
	$Return['Error']=1;
	$Return['Msg']='You are already subscribed with us!';
	if($Web->num_rows()==0)
	{
		$Web->query("insert into subscriptions set Email='$SubsEmail', Date='$CurrentDate'");
		$Return['Error']=0;
		$Return['Msg']='You are subscribed successfully!';
	}
	echo json_encode($Return);
}

if(isset($_REQUEST['ContactFormFlag']) && $_REQUEST['ContactFormFlag']=='true')
{
	extract($_REQUEST);
	ob_start();
?>
<table width="100%" cellpadding="5" cellspacing="0" border="0" bordercolor="#000000" style="border-collapse:collapse;">
	<tr>
        <td width="25%" align="right" style="padding:5px;">Name: </td>
        <td width="75%" align="left" style="padding:5px;"><?php echo $Name; ?></td>
    </tr>
    <tr>
        <td align="right" style="padding:5px;">Email: </td>
        <td align="left" style="padding:5px;"><?php echo $Email; ?></td>
    </tr>
    <tr>
        <td align="right" style="padding:5px;">Phone: </td>
        <td align="left" style="padding:5px;"><?php echo $Phone; ?></td>
    </tr>
    <tr>
        <td align="right" style="padding:5px;">Subject: </td>
        <td align="left" style="padding:5px;"><?php echo $Subject; ?></td>
    </tr>
    <tr>
        <td align="right" style="padding:5px;">Message: </td>
        <td align="left" style="padding:5px;"><?php echo $Message; ?></td>
    </tr>
</table>
<?php	
	$HTMLMessage = ob_get_contents();
	ob_end_clean();
	$Return["Error"]= 0;
	$Return["Msg"]	= $_SESSION[WEBSITE_SETTINGS][8];
	
	$mail->Subject = 'New Inquiry from Website';
	$mail->addAddress(EMAIL_ADDRESS_FOR_INQUIRY, SYSTEM_EMAIL_FROM_NAME);
	$mail->SetFrom(SYSTEM_EMAIL_FROM_EMAIL, SYSTEM_EMAIL_FROM_NAME);
	$mail->addReplyTo($Email, $Name);
	$mail->msgHTML($HTMLMessage);
	$mail->AltBody = 'Name: '.$Name.' --- Email: '.$Email.' --- Phone: '.$Phone.' --- Subject: '.$Subject.' --- Message: '.$Message;
	$mail->send();
	echo json_encode($Return);
}

if(isset($_REQUEST['AddToCartFlag']) && $_REQUEST['AddToCartFlag']=='true')
{
	extract($_REQUEST);
	$Done=0;
	if($Color=='')
	{
		$Return["Error"]="1";
		$Return["Msg"]="Please select color";
	}
	else if($Size=='')
	{
		$Return["Error"]="1";
		$Return["Msg"]="Please select size";
	}
	else if($Quantity=='')
	{
		$Return["Error"]="1";
		$Return["Msg"]="Please select quantity";
	}
	else
	{
		if(count($_SESSION[USER_CART])>0)
		{
			foreach($_SESSION[USER_CART] as $Key => $Cart)
			{
				if($Cart["ID"]==$ProductID && 
				   $Cart["Size"]==$Size && 
				   $Cart["Color"]==$Color)
				{
					$_SESSION[USER_CART][$Key]["Quantity"] = $_SESSION[USER_CART][$Key]["Quantity"] + 1;
					$Done=1;
					break;
				}
			}
		}
		if($Done==0)
		{
			$_SESSION[USER_CART][] = array("ID"=>			$ProductID, 
											"Size"=>		$Size, 
											"Color"=>		$Color, 
											"Quantity"=>	$Quantity);
		}
	
		$Return["Error"]="0";
		$Return["Msg"]="Product added in cart";
		$Return["CartCount"]=$Products->CountCart();
	}
	echo json_encode($Return);
}

if(isset($_REQUEST['DeleteCart']) && $_REQUEST['DeleteCart']=='true')
{
	unset($_SESSION[USER_CART][$_REQUEST['CartID']]);
	$Return["Error"]="0";
	$Return["Msg"]="Cart updated successfully";
	$Return["CartCount"]=$Products->CountCart();
	echo json_encode($Return);
}

if(isset($_REQUEST['UpdateCart']) && $_REQUEST['UpdateCart']=='true')
{
	// first check quantity

	$Quantity = $_SESSION[USER_CART][$_REQUEST['CartID']]["Quantity"];
	
	if($_REQUEST['ToDo']=='p')
	{
		$_SESSION[USER_CART][$_REQUEST['CartID']]["Quantity"] = $_SESSION[USER_CART][$_REQUEST['CartID']]["Quantity"] + 1;
	}
	else
	{
		if($Quantity>1)
			$_SESSION[USER_CART][$_REQUEST['CartID']]["Quantity"] = $_SESSION[USER_CART][$_REQUEST['CartID']]["Quantity"] - 1;
		else
			unset($_SESSION[USER_CART][$_REQUEST['CartID']]);
	}
	
	$Return["Error"]="0";
	$Return["Msg"]="Cart updated successfully";
	$Return["CartCount"]=$Products->CountCart();
	echo json_encode($Return);
}

if(isset($_REQUEST['ChangePasswordFlag']) && $_REQUEST['ChangePasswordFlag']=='true')
{
	$Web->query("select * from web_users where 
	Email='".$_SESSION[WEB_USER_SESSION]["Email"]."' and 
	Password='".md5($OldPassword)."' and 
	Status='".ACTIVE."'");
	if($Web->num_rows()>0)
	{
		$Web->query("update web_users set 
		Password='".md5($NewPassword)."' where 
		Email='".$_SESSION[WEB_USER_SESSION]["Email"]."'");
		$Return["Error"]=0;
		$Return["Msg"]='Password changed successfully.';
	}
	else
	{
		$Return["Error"]=1;
		$Return["Msg"]='Your old password is wrong. Try again.';
	}
	echo json_encode($Return);
}

if(isset($_REQUEST['ForgetPasswordFlag']) && $_REQUEST['ForgetPasswordFlag']=='true')
{
	$Web->query("select * from web_users where Email='$ForgetEmail'");
	if($Web->num_rows()>0)
	{
		$NewPassword = $Web->GeneratePassword(7);
		$Return["Error"]=0;
		$Return["Msg"]='New password sent to your email addres';
		$UserDetails = $Web->getRecord($ForgetEmail, "Email", "web_users");
		$MailMessage = 'Hello '.$UserDetails["Name"].'<br /><br />Your new password is '.$NewPassword;
		$Web->SendEmail($ForgetEmail, 'New password from LP Air Con', $MailMessage);
		$Web->query("update web_users set Password='".md5($NewPassword)."' where Email='$ForgetEmail'");
	}
	else
	{
		$Return["Error"]=1;
		$Return["Msg"]='User with this email address do not exists.';
	}
	echo json_encode($Return);
}

if(isset($_REQUEST['LoginFormFlag']) && $_REQUEST['LoginFormFlag']=='true')
{
	$Password = md5($_REQUEST['LoginPassword']);
	$test_query="select * from web_users where Email='".$_REQUEST['LoginEmail']."' and Password='$Password'";
	$Web->query($test_query);
	if($Web->num_rows()>0)
	{
		$Web->next_Record();
		if($Web->f('Status')==ACTIVE)
		{
			$_SESSION[WEB_USER_SESSION] = $Web->getRecord($_REQUEST['LoginEmail'], "Email", "web_users");
			$Return["Error"]=0;
			$Return["Msg"]='Logging in. Please wait...';
		}
		else
		{
			$Return["Error"]=1;
			$Return["Msg"]='Your account is not active.';
		}
	}
	else
	{
		$Return["Error"]=1;
		$Return["Msg"]='Wrong login details. Try again.<BR>';
	}
	if(isset($_SESSION['isCheckout']))
		$Return['Url']=WEB_URL.'checkout.html';
	else
		$Return['Url']=WEB_URL;
	echo json_encode($Return);
}

if(isset($_REQUEST['RegisterFormFlag']) && $_REQUEST['RegisterFormFlag']=='true')
{
	$Password = md5($_REQUEST['RegisterPassword']);
	$Web->query("insert into web_users set 
	Name='".$_REQUEST['Name']."', 
	Email='".$_REQUEST['RegisterEmail']."', 
	Mobile='".$_REQUEST['Mobile']."', 
	Password='".$Password."', 
	JoinDate='$CurrentDate'");
	
	$UserID = $Web->MysqlInsertID();
	
	$Link = WEB_URL.'activate-user.php?user='.$Web->convertString("GW-".$UserID."-GW");
	
	$EmailMessage = 'Hello '.$Name.'
	<br /><br />Thanks for registration in LP Air Con website. Please <a href="'.$Link.'">click here</a> to activate your account or copy paste the below link<br /><br /><a href="'.$Link.'">'.$Link.'</a>';
	$Web->SendEmail($_REQUEST['RegisterEmail'], "LP Air Con Account Activation Require", $EmailMessage);
	
	$Return["Error"]="0";
	$_SESSION[WEB_MSG]['Title']='Thank you for registering. Please first confirm your email account';
	$_SESSION[WEB_MSG]['Desc']='You will receive an email shortly. Click on activate account or copy paste the given url. Please check your junk email and spam also.';
	echo json_encode($Return);
}

if(isset($_REQUEST['ValidateEmail']) && $_REQUEST['ValidateEmail']=='true')
{
	$Web->query("select * from web_users where Email='$Email'");
	if($Web->num_rows()>0)
	{
		$Return["Error"]="1";
		$Return["Msg"]="Email address already exists";
	}
	else
	{
		$Return["Error"]="0";
		$Return["Msg"]="Email address available";
	}
	echo json_encode($Return);
}

if(isset($_REQUEST['CheckoutFormFlag']) && $_REQUEST['CheckoutFormFlag']=='true')
{
	$Date = date("Y-m-d", strtotime($_REQUEST['PreferredDate']));
	$Time = date("H:i:s", strtotime($_REQUEST['$PreferredTime']));
	
	if($_SESSION[WEB_USER_SESSION]['Email']=='')
	{
		$_SESSION[WEB_USER_SESSION]['Email'] = $_REQUEST['Email'];
		$Web->query("update web_users set Email='".$_REQUEST['Email']."' 
		where TableID='".$_SESSION[WEB_USER_SESSION]["TableID"]."'");
	}
	
	if($_SESSION[WEB_USER_SESSION]['Mobile']=='')
	{
		$_SESSION[WEB_USER_SESSION]['Mobile'] = $_REQUEST['Phone'];
		$Web->query("update web_users set Mobile='".$_REQUEST['Phone']."' 
		where TableID='".$_SESSION[WEB_USER_SESSION]["TableID"]."'");
	}
	
	$Web->query("select * from orders where OrderDate='$CurrentDate'");
	$CountTodaysOrders = $Web->num_rows();
	$CountTodaysOrders++;
	$CountTodaysOrders = sprintf("%04d", $CountTodaysOrders);
	$OrderNumber = $CurrentDate."-".$CountTodaysOrders;
	
	$CountProducts = $Products->CountCartProduct();
	
	if($_REQUEST['DiscountCode']=='')
	{
		$Web->query("insert into orders set 
		OrderID='$OrderNumber', 
		UserID='".$_SESSION[WEB_USER_SESSION]["TableID"]."', 
		OrderDate='$CurrentDate', 
		Phone='".$_REQUEST['Phone']."', 
		Address='".$_REQUEST['Address']."', 
		Comments='".$_REQUEST['Comments']."', 
		Currency='".$_SESSION['Currency']."', 
		PaymentMode='".$_REQUEST['PaymentMode']."', 
		PreferredDate='$Date', 
		PreferredTime='$Time', 
		PCount='$CountProducts'");
	}
	else
	{
		$DCData = $Web->getRecord($_REQUEST['DiscountCode'], 'Code', 'discounts');
		$Web->query("insert into orders set 
		OrderID='$OrderNumber', 
		UserID='".$_SESSION[WEB_USER_SESSION]["TableID"]."', 
		OrderDate='$CurrentDate', 
		Phone='".$_REQUEST['Phone']."', 
		Address='".$_REQUEST['Address']."', 
		Comments='".$_REQUEST['Comments']."',
		Currency='".$_SESSION['Currency']."', 
		PaymentMode='".$_REQUEST['PaymentMode']."',
		PreferredDate='$Date', 
		PreferredTime='$Time', 
		PCount='$CountProducts', 
		DCode='".$_REQUEST['DiscountCode']."', 
		DValue='$DCData[Discount]'");
	}
	
	$OrderID = $Web->MysqlInsertID();
	$T_Quantity=0;
	$TotalAmount='';
	foreach($_SESSION[USER_CART] as $Key => $Cart)
	{
		$Product	= $Web->getRecord($Cart["ID"], "TableID", "products");
		$Size		= $Web->getRecord($Cart["Size"], "TableID", "p_size");
		$Color		= $Web->getRecord($Cart["Color"], "TableID", "p_color");
		$Quantity	= $Cart["Quantity"];
		$T_Quantity	= $T_Quantity + $Quantity;
		$Price		= $Product["Price"];
		$T_Price	= $Product["Price"] * $Quantity;

		$OnlyAmount = str_replace($_SESSION['Currency']." ", "", $T_Price);
		$TotalAmount = $TotalAmount + $OnlyAmount;
		
		$InPromotion=INACTIVE;
		$InWholeSale=INACTIVE;
		$Type=1;

		$OriginalPrice		= $Product["Price"];
		$Sale_Price		= $Product["Sale_Price"];
		$WholeSale_Price	= $Product["WholeSale_Price"];
		$WholeSale_Price	= 0;
		

		$Web->query("insert into order_detail set 
		OrderID='$OrderID', 
		Type='$Type', 
		ProductID='$Cart[ID]', 
		Color='$Color[Title]', 
		Size='$Size[Title]', 
		Quantity='$Quantity', 
		InPromotion='$InPromotion', 
		InWholeSale='$InWholeSale', 
		OriginalPrice='$OriginalPrice', 
		Sale_Price='$Sale_Price', 
		WholeSale_Price='$WholeSale_Price'");
		
		$Web->query("update products set Stock=Stock-$Quantity where TableID='$Cart[ID]'");
	}
	
	$GrandTotal = $TotalAmount;
	//$Rate = $Products->ConvertAmount('AED', 'USD');
	//$TotalAmountinUSD = $GrandTotal * $Rate;
	$TotalAmountinAED = $GrandTotal;
	
	if($_REQUEST['DiscountCode']!='')
	{
		$AmountOne = $TotalAmountinAED / 100;
		$AmountTwo = $AmountOne * $DCData["Discount"];
		$AmountThree = $TotalAmountinAED - $AmountTwo;
		$TotalAmountinAED = $AmountThree;
	}
	if($_REQUEST['PaymentMode']==1)
	{
		$_SESSION['DoPayment']['Description'] = $_SESSION[WEB_USER_SESSION]["Name"].' | '.$OrderNumber;
		$_SESSION['DoPayment']['NotifyURL'] = WEB_URL.'confirm-order.php?order='.$Web->convertString($OrderNumber);
		$_SESSION['DoPayment']['ReturnURL'] = WEB_URL.'thank-you.html';
		$_SESSION['DoPayment']['OrderNumber'] = $OrderNumber;
		$_SESSION['DoPayment']['AmountToPay'] = $TotalAmountinAED;
		$GoToPage = WEB_URL.'do-payment.html';
		$Return['UrlType'] = 'Payment';

	}
	else
	{
		$GoToPage = WEB_URL.'confirm-order.php?order='.$Web->convertString($OrderNumber);
		$Return['UrlType'] = 'COD';
	}
	//unset($_SESSION[USER_CART]);
	$Return['Url'] = $GoToPage;
	echo json_encode($Return);
}

if(isset($_REQUEST['ValidateDicsount']) && $_REQUEST['ValidateDicsount']=='true')
{
	extract($_REQUEST);
	$Web->query("select * from discounts where Code='$Code'");
	if($Web->num_rows()>0)
	{
		$Web->next_Record();
		if($Web->f('Used')==1)
		{
			$Return["Error"]='1';
			$Return["Msg"]='Discount code already used.';
		}
		else
		{
			$Return["Error"]='0';
			$Return["Msg"]='Congratulations! You got '.$Web->f('Discount').'% discount. Now click on checkout.';
			$Web->query("update discounts set 
			Used='1', 
			UsedDate='$CurrentDate', 
			UserID='".$_SESSION[WEB_USER_SESSION]["TableID"]."' 
			where Code='$Code'");
		}
	}
	else
	{
		$Return["Error"]='1';
		$Return["Msg"]='You entered wrong discount code.';
	}
	echo json_encode($Return);
}

if(isset($_REQUEST['FbLogin']) && $_REQUEST['FbLogin']=='true')
{
	$Web->query("select * from web_users where Email='$email'");
	if($Web->num_rows()==0)
	{
		$Web->query("insert into web_users set 
		Name='$name', 
		Email='$email', 
		JoinDate='$CurrentDate', 
		Status='1', 
		fbID='$id'");
	}
	else
	{
		$Web->next_Record();
		if($Web->f('fbID')=='')
		{
			$Web->query("update web_users set fbID='$id' where Email='$email'");
		}
		if($Web->f('Status')==INACTIVE)
		{
			$Web->query("update web_users set Status='".ACTIVE."' where Email='$email'");
		}
	}
	$_SESSION[WEB_USER_SESSION] = $Web->getRecord($email, "Email", "web_users");
	
	if(isset($_SESSION['isCheckout']))
		$Return['Url']=WEB_URL.'checkout.html';
	else
		$Return['Url']=WEB_URL;
	
	echo json_encode($Return);
}
?>