<?php
include_once("classes/config.php");

if(isset($_REQUEST['order']))
{
	$OrderID = $Web->convertString($_REQUEST['order'], HANDLER, 'D');
	
	$Web2->query("update orders set PaymentDone='".ACTIVE."' where OrderID='$OrderID'");
	
	$Web->query("select * from orders where OrderID='$OrderID'");
	if($Web->num_rows()>0)
	{
		$Web->next_Record();
		$ProductCount = $Web->f('PCount');
		
		$UserDetails = $Web3->getRecord($Web->f('UserID'), "TableID", "web_users");
		
		
		$OrderStatus = $Web3->getFieldData("Title", "TableID", $Web->f('OrderStatus'), 'order_status');
		
		$Web2->query("select sum(Quantity) as TotalItems from order_detail where OrderID='".$Web->f('TableID')."'");
		$Web2->next_Record();
		$TotalItems			= $Web2->f('TotalItems');
		
		if(!is_numeric($TotalItems))
			$TotalItems=0;
		
		$Web2->query("select sum(OriginalPrice) as OriginalPrice from order_detail where	OrderID='".$Web->f('TableID')."' and InPromotion='".INACTIVE."' and InWholeSale='".INACTIVE."'");
		$Web2->next_Record();
		$OriginalPrice	= $Web2->f('OriginalPrice');
		if(!is_numeric($OriginalPrice))
			$OriginalPrice=0;
		
		$Web2->query("select sum(Sale_Price) as Sale_Price from order_detail where	OrderID='".$Web->f('TableID')."' and InPromotion='".ACTIVE."' and InWholeSale='".INACTIVE."'");
		$Web2->next_Record();
		$Sale_Price	= $Web2->f('Sale_Price');
		if(!is_numeric($Sale_Price))
			$Sale_Price=0;
		
		$Web2->query("select sum(Sale_Price) as Sale_Price from order_detail where	OrderID='".$Web->f('TableID')."' and InPromotion='".INACTIVE."' and InWholeSale='".ACTIVE."'");
		$Web2->next_Record();
		$WholeSale_Price	= $Web2->f('Sale_Price');
		if(!is_numeric($WholeSale_Price))
			$WholeSale_Price=0;
		
		$TotalAmount = $OriginalPrice + $Sale_Price + $WholeSale_Price;
		ob_start();
?>
<table cellpadding="5" cellspacing="0" border="1" style="border-collapse:collapse;" bordercolor="#000000">
	<tr>
    	<td align="left"><strong>Order Number: </strong></td>
        <td align="left"><?php echo $OrderID; ?></td>
    </tr>
    <tr>
    	<td align="left"><strong>Order Date: </strong></td>
        <td align="left"><?php echo $Web->FormatDate($Web->f('OrderDate'), "F d Y"); ?></td>
    </tr>
    <tr>
    	<td align="left"><strong>Order Status: </strong></td>
        <td align="left"><?php echo $OrderStatus; ?></td>
    </tr>
    <tr>
    	<td align="left"><strong>Payment Mode:</strong></td>
        <td align="left"><?php echo $PaymentMode[$Web->f('PaymentMode')]; if($Web->f('PaymentMode')==1) { if($Web->f('PaymentDone')==ACTIVE) { echo "Payment Done"; } else { echo "Payment Not Done"; } } ?></td>
    </tr>
    <tr>
    	<td align="left"><strong>Preferred Date: </strong></td>
        <td align="left"><?php echo $Web->FormatDate($Web->f('PreferredDate'), "F d Y"); ?></td>
    </tr>
    <tr>
    	<td align="left"><strong>Preferred Time: </strong></td>
        <td align="left"><?php echo $Web->FormatDate($Web->f('PreferredTime'), "h:i a"); ?></td>
    </tr>
    <tr>
    	<td align="left"><strong>Total Products: </strong></td>
        <td align="left"><?php echo $ProductCount; ?></td>
    </tr>
    <tr>
    	<td align="left"><strong>Total Amount: </strong></td>
        <td align="left"><?php echo DEFAULT_CURRENCY." ".number_format($TotalAmount); ?></td>
    </tr>
    <?php
		if($Web->f('DCode')!='')
		{
			$AmountOne = $TotalAmount / 100;
			$AmountTwo = $AmountOne * $Web->f('DValue');
			$AmountThree = $TotalAmount - $AmountTwo;
	?>
    <tr>
    	<td align="left"><strong>Dicount: </strong></td>
        <td align="left"><?php echo $Web->f('DValue'); ?>%</td>
    </tr>
    <tr>
    	<td align="left"><strong>Grand Total: </strong></td>
        <td align="left"><?php echo DEFAULT_CURRENCY." ".$AmountThree; ?></td>
    </tr>
    <?php		
		}
	?>
</table>
<?php if($ProductCount>0) { ?>
<br /><br />
This is the list of products that you have bought:
<table width="100%" cellpadding="5" cellspacing="0" border="1" style="border-collapse:collapse;" bordercolor="#000000">
<tr>
	<td width="5%" align="center"><strong>S.No</strong></td>
    <td width="15%" align="left"><strong>Type</strong></td>
    <td width="25%" align="left"><strong>Product</strong></td>
    <!--<td width="10%" align="left"><strong>Size</strong></td>
    <td width="10%" align="left"><strong>Color</strong></td>-->
    <td width="10%" align="left"><strong>Brand</strong></td>
    <td width="10%" align="left"><strong>Quantity</strong></td>
    <td width="10%" align="left"><strong>Price</strong></td>
    <td width="15%" align="left"><strong>Total Price</strong></td>
</tr>
<?php
	$Sno=0;
	$Web2->query("select * from order_detail where OrderID='".$Web->f('TableID')."'");
	while($Web2->next_Record())
	{
		$Sno++;
		$Product	= $Web3->getRecord($Web2->f('ProductID'), "TableID", "products");
		
		$Brand	= $Web3->getRecord($Product['BrandID'], "TableID", "p_brands");
		
		$Price = $Web2->f('OriginalPrice');
		$TotalPrice = $Web2->f('Quantity') * $Price;
?>
<tr>
	<td align="center"><?php echo $Sno; ?></td>
    <td align="left"><?php echo $ProductType[$Web2->f('Type')]; ?></td>
    <td align="left"><?php echo $Product["Title"]." - ".$Product["Code"]; ?></td>
   <!-- <td align="left"><?php //echo $Web2->f('Size'); ?></td>
    <td align="left"><?php //echo $Web2->f('Color'); ?></td>-->
    <td align="left"><?php echo $Brand["Title"]; ?></td>
    <td align="left"><?php echo $Web2->f('Quantity'); ?></td>
    <td align="left"><?php echo $Web->f('Currency')." ".number_format($Price); ?></td>
    <td align="left"><?php echo $Web->f('Currency')." ".number_format($TotalPrice); ?></td>
</tr>
<?php		
	}
?>
</table>
<?php }
		$OrderHTML = ob_get_contents();
		ob_end_clean();
		
		
		$_SESSION[WEB_MSG]['Title']='Thank you for your order';
		$_SESSION[WEB_MSG]['Desc']='We have received all details. A confirmation email also sent to your email account';

		
		// Email to user
		$Mailstarter_User = 'Hello '.$UserDetails["Name"].'<br /><br />The below is your order details.';
		$UserEmailMessage = $Mailstarter_User.$OrderHTML;
		$Web->SendEmail($UserDetails["Email"], "[".$Web->f('OrderID')."] order details from LP Air Con", $UserEmailMessage);
		
		// Email to admin
		$Mailstarter_Admin = $UserDetails["Name"].' bought products fom LP Air Con<br /><br />The below is order details.';
		$AdminEmailMessage = $Mailstarter_Admin.str_replace("you have bought", "user has bought", $OrderHTML);
		$Web->SendEmail(EMAIL_ADDRESS_FOR_INQUIRY, "[".$Web->f('OrderID')."] order details from LP Air Con", $AdminEmailMessage);
		
		//$Web->Redirect(WEB_URL."thank-you.html");
	}
}
//$Web->Redirect(WEB_URL);
?>