<?php
if(isset($_REQUEST['refreshAjax']))
	include_once("classes/config.php");
	
	$ProductCount = $Products->CountCartProduct();
?>
<div class="cart-summary">
<h1>Order Summary</h1>
<?php
$TotalAmount			= 0;
$ItemTotalWeight		= 0;
$TotalWeight			= 0;
$TotalShippingCharges	= 0;
if($ProductCount>0)
{
foreach($_SESSION[USER_CART] as $Key => $Cart)
{
    $Quantity	= $Cart["Quantity"];
    
    $Product	= $Web->getRecord($Cart["ID"], "TableID", "products");
    $Price		= $Product["Price"];
    $T_Price	= $Product["Price"] * $Quantity;
    
    $OnlyAmount = str_replace($_SESSION['Currency']." ", "", $T_Price);
    $TotalAmount = $TotalAmount + $OnlyAmount;
	$ItemWeight			= $Product["ItemWeight"];
	$ItemTotalWeight	= $Quantity * $ItemWeight;
	$TotalWeight		= $TotalWeight + $ItemTotalWeight;
?>
<div class="order-item">
<table width="100%" cellpadding="2" cellspacing="0" border="0">
<tr>
<td align="left" colspan="2"><strong><?php echo $Product["Title"]; ?></strong></td>
</tr>
<tr>
<td align="left">Price</td>
<td align="right"><?php echo $Quantity." x ".$Price." = ".$T_Price; ?></td>
</tr>
</table>
</div>
<?php		
}
$TotalAmountWithShipment = $TotalAmount + $TotalShippingCharges;
?>
<div align="right" class="total-amount"><strong>Total Product Price = <?php echo $_SESSION['Currency']." ".number_format($TotalAmount); ?></strong></div>
<?php
}
?>
</div>