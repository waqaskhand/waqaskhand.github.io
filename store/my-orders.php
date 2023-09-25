<?php
include_once("classes/config.php");
if(!isset($_SESSION[WEB_USER_SESSION]))
	$Web->Redirect(WEB_URL);
	
$MetaTitle = 'My Orders';
include_once("html_header.php");
include_once("header.php");

?>
<section class="main-content inner-page no-margin">
<div class="clear height5"></div>
    <div class="container">
        <div class="content-div">
            <div class="heading"><h2>My Orders</h2></div>
            <table id="miyazaki">
            	<thead>
                	<tr>
                        <th>Order Number</td>
                        <th>Order Date</td>
                        <th>Order Status</td>
                        <th>Payment Mode</td>
                        <th>Products</td>
                        <th>Total Amount</td>
                        <th>Details</td>
                    </tr>
                </thead>
                <tbody>
                <?php
                	$Web->query("select * from orders where UserID='".$_SESSION[WEB_USER_SESSION]["TableID"]."' order by TableID desc");
					if($Web->num_rows()==0)
					{
				?>
                	<tr>
                    	<td colspan="8" align="center" class="no-record">Cart is empty</td>
                    </tr>
                <?php } else {
						 $Sno=0;
						while($Web->next_Record())
						{
							$Sno++;
							
							$Web2->query("select 
							sum(Quantity) as TotalItems 
							from order_detail where OrderID='".$Web->f('TableID')."'");
							$Web2->next_Record();
							$TotalItems			= $Web2->f('TotalItems');
							if(!is_numeric($TotalItems))
								$TotalItems=0;
								
							$Web2->query("select 
							sum(OriginalPrice) as OriginalPrice 
							from order_detail where 
							OrderID='".$Web->f('TableID')."' and 
							InPromotion='".INACTIVE."' and 
							InWholeSale='".INACTIVE."'");
							$Web2->next_Record();
							$OriginalPrice	= $Web2->f('OriginalPrice');
							if(!is_numeric($OriginalPrice))
								$OriginalPrice=0;
							
							$Web2->query("select 
							sum(Sale_Price) as Sale_Price 
							from order_detail where 
							OrderID='".$Web->f('TableID')."' and 
							InPromotion='".ACTIVE."' and 
							InWholeSale='".INACTIVE."'");
							$Web2->next_Record();
							$Sale_Price	= $Web2->f('Sale_Price');
							if(!is_numeric($Sale_Price))
								$Sale_Price=0;
							
							$Web2->query("select 
							sum(Sale_Price) as Sale_Price 
							from order_detail where 
							OrderID='".$Web->f('TableID')."' and 
							InPromotion='".INACTIVE."' and 
							InWholeSale='".ACTIVE."'");
							$Web2->next_Record();
							$WholeSale_Price	= $Web2->f('Sale_Price');
							if(!is_numeric($WholeSale_Price))
								$WholeSale_Price=0;
								
							$TotalAmount = $OriginalPrice + $Sale_Price + $WholeSale_Price;
							
							$OrderStatus = $Web2->getFieldData("Title", "TableID", $Web->f('OrderStatus'), 'order_status');
							
							$GrandTotal = $TotalAmount;
        		?>
                <tr>
                    <td><?php echo $Web->f('OrderID'); ?></td>
                    <td><?php echo $Web->FormatDate($Web->f('OrderDate'), "F d Y"); ?></td>
                    <td><?php echo $OrderStatus; ?></td>
                    <td><?php echo $PaymentMode[$Web->f('PaymentMode')]; ?></td>
                    <td><?php echo number_format($TotalItems); ?></td>
                    <td><?php echo $Web->f('Currency')." ".number_format($GrandTotal); ?></td>
                    <td><a href="<?php echo WEB_URL; ?>order/<?php echo $Web->f('TableID'); ?>.html"><img src="<?php echo IMAGES_PATH; ?>detail.png" alt="" /></a></td>
                </tr>
        		<?php
        				}
                	}
				?>
                </tbody>
            </table>
            <div align="right" class="cart-btns"><?php if($Count>0) { ?>
    	<input type="button" name="ContinueShopping" id="ContinueShopping" value="+ Continue Shopping" class="Button" onclick="window.location.href='<?php echo WEB_URL; ?>';" />&nbsp;<input type="button" name="CheckOut" id="CheckOut" value="Checkout &rarr;" class="Button" onclick="window.location.href='<?php echo WEB_URL; ?>checkout.html';" />
    <?php } ?></div>
        </div>
    </div>
</section>
<?php
	include_once("subscription.php");
	include_once("footer.php");
	include_once("html_footer.php");
?>