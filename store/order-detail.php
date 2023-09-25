<?php
include_once("classes/config.php");

if(isset($_REQUEST['Keyword']) && $_REQUEST['Keyword']!='')
{
	$Web3->query("select * from orders where TableID='".$_REQUEST['Keyword']."' and UserID='".$_SESSION[WEB_USER_SESSION]["TableID"]."'");
	
	if($Web3->num_rows()>0)
	{
		include_once("html_header.php");
		
		include_once("header.php");
		
		$Web3->next_Record();
		$MetaTitle = 'Order &ldquo;'.$Web3->f('OrderID').'&rdquo; details';
		$ProductCount = $Web3->f('PCount');
		$OrderStatus = $Web2->getFieldData("Title", "TableID", $Web3->f('OrderStatus'), 'order_status');
		
		$Web2->query("select 
		sum(Quantity) as TotalItems 
		from order_detail where OrderID='".$Web3->f('TableID')."'");
		$Web2->next_Record();
		$TotalItems			= $Web2->f('TotalItems');
		if(!is_numeric($TotalItems))
			$TotalItems=0;
		
		$Web2->query("select 
		sum(OriginalPrice) as OriginalPrice 
		from order_detail where 
		OrderID='".$Web3->f('TableID')."' and 
		InPromotion='".INACTIVE."' and 
		InWholeSale='".INACTIVE."'");
		$Web2->next_Record();
		$OriginalPrice	= $Web2->f('OriginalPrice');
		if(!is_numeric($OriginalPrice))
			$OriginalPrice=0;
		
		$Web2->query("select 
		sum(Sale_Price) as Sale_Price 
		from order_detail where 
		OrderID='".$Web3->f('TableID')."' and 
		InPromotion='".ACTIVE."' and 
		InWholeSale='".INACTIVE."'");
		$Web2->next_Record();
		$Sale_Price	= $Web2->f('Sale_Price');
		if(!is_numeric($Sale_Price))
			$Sale_Price=0;
		
		$Web2->query("select 
		sum(Sale_Price) as Sale_Price 
		from order_detail where 
		OrderID='".$Web3->f('TableID')."' and 
		InPromotion='".INACTIVE."' and 
		InWholeSale='".ACTIVE."'");
		$Web2->next_Record();
		$WholeSale_Price	= $Web2->f('Sale_Price');
		if(!is_numeric($WholeSale_Price))
			$WholeSale_Price=0;
		
		$TotalAmount = $OriginalPrice + $Sale_Price + $WholeSale_Price;
		
		$tCurrency = $Web3->f('Currency');
?>
<section class="main-content inner-page no-margin">
<div class="clear height5"></div>
    <div class="container">
        <div class="content-div">
            <div class="heading"><h2><?php echo $MetaTitle; ?></h2></div>
            <table cellpadding="5" cellspacing="0" class="order-detail">
            <tr>
            <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
            <td align="left"><strong>Order Number: </strong></td>
            <td align="left"><?php echo $Web3->f('OrderID'); ?></td>
            </tr>
            <tr>
            <td align="left"><strong>Order Date: </strong></td>
            <td align="left"><?php echo $Web->FormatDate($Web3->f('OrderDate'), "F d Y"); ?></td>
            </tr>
            <tr>
            <td align="left"><strong>Order Status: </strong></td>
            <td align="left"><?php echo $OrderStatus; ?></td>
            </tr>
            <tr>
            <td align="left"><strong>Payment Mode:</strong></td>
            <td align="left"><?php echo $PaymentMode[$Web3->f('PaymentMode')]; ?></td>
            </tr>
            <tr>
            <td align="left"><strong>Total Products: </strong></td>
            <td align="left"><?php echo $ProductCount; ?></td>
            </tr>
            <tr>
            <td align="left" valign="top"><strong>Total Amount:</strong></td>
            <td align="left" valign="top"><?php echo $tCurrency." ".number_format($TotalAmount); ?></td>
            </tr>
            <tr>
            <td align="left" valign="top"><strong>Shipping Details: </strong></td>
            <td align="left" valign="top"><?php echo nl2br($Web3->f('Address')); ?></td>
            </tr>
            <tr>
            <td align="left"><strong>Special Instructions: </strong></td>
            <td align="left"><?php echo nl2br($Web3->f('Comments')); ?></td>
            </tr>
            </table>
            <br /><br /><p>This is the list of products that you have bought:</p>
            <table id="miyazaki">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Size</th>
                        <th>Color</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total Price</th>
                    </tr>
				</thead>
                <tbody>
<?php
	$Sno=0;
	$Web2->query("select * from order_detail where OrderID='".$Web3->f('TableID')."'");
	while($Web2->next_Record())
	{
		$Sno++;
		$Product	= $Web3->getRecord($Web2->f('ProductID'), "TableID", "products");
		$Price = $Web2->f('OriginalPrice');
		$TotalPrice = $Web2->f('Quantity') * $Price;
?>
<tr>
    <td><?php echo $Product["Title"]." - ".$Product["Code"]; ?></td>
    <td><?php echo $Web2->f('Size'); ?></td>
    <td><?php echo $Web2->f('Color'); ?></td>
    <td><?php echo $Web2->f('Quantity'); ?></td>
    <td><?php echo $tCurrency." ".number_format($Price); ?></td>
    <td><?php echo $tCurrency." ".number_format($TotalPrice); ?></td>
</tr>
<?php		
	}
?>
</tbody>
</table>
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
}
else
	$Web->Redirect(WEB_URL);
?>