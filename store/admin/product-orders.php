<?php
if(isset($_REQUEST['OrderID']) && $_REQUEST['OrderID']!='')
{
	$Web->query("select * from orders where TableID='$OrderID'");
	if($Web->num_rows()==0)
	{
		$Web->Redirect("index.php?page=product-orders");
	}
	$Web->next_Record();
	$ProductCount = $Web->f('PCount');
	$ServiceCount = $Web->f('SCount');
	$OrderStatus = $Web2->getFieldData("Title", "TableID", $Web->f('OrderStatus'), 'order_status');
	$UserDetails = $Web2->getRecord($Web->f('UserID'), "TableID", "web_users");
	
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
	
	//$ShipmentCountry = $Web2->getFieldData("Title", "TableID", $Web->f('ShipmentCountryID'), "shipment_country");
	$ShipmentCountry='';
?>
<div class="Page-Data">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
    	<td width="95%" align="left"><h1>Order &ldquo;<?php echo $Web->f('OrderID'); ?>&rdquo; details</h1></td>
        <td width="5%" align="right"><a href="javascript:history.back();">&lt;&lt;Back</a></td>
    </tr>
</table>
<table width="100%" cellpadding="5" cellspacing="0">
	<tr>
	  <td align="left">&nbsp;</td>
	  <td align="left">&nbsp;</td>
    </tr>
	<tr>
    	<td width="18%" align="left"><strong>Order Number: </strong></td>
        <td width="82%" align="left"><?php echo $Web->f('OrderID'); ?></td>
    </tr>
    <tr>
    	<td align="left"><strong>Order Date: </strong></td>
        <td align="left"><?php echo $Web->FormatDate($Web->f('OrderDate'), "F d Y"); ?></td>
    </tr>
    <tr>
    	<td align="left"><strong>Order Status: </strong></td>
        <td align="left">
        <form name="UpdateOrderStatusForm" id="UpdateOrderStatusForm" method="post" action="">
        	<input type="hidden" name="UpdateStatusFlag" id="UpdateStatusFlag" value="true" />
            <input type="hidden" name="OrderID" id="OrderID" value="<?php echo $OrderID; ?>" />
            <select name="OrderStatus" id="OrderStatus" class="DropDown" onchange="UpdateOrderStatus();">
            <?php
				echo $Web2->getSelectDropDown($Web->f('OrderStatus'), 'TableID', 'Title', 'order_status', 'Title');
			?>
            </select>&nbsp;<span id="StatusMsg"></span>
        </form>
        </td>
    </tr>
    <tr>
      <td align="left"><strong>Payment Mode:</strong></td>
      <td align="left"><?php echo $PaymentMode[$Web->f('PaymentMode')]; if($Web->f('PaymentMode')==1) { if($Web->f('PaymentDone')==ACTIVE) { echo "Payment Done"; } else { echo "Payment Not Done"; } } ?></td>
    </tr>
    <tr>
    	<td align="left"><strong>Preferred Date:</strong></td>
        <td align="left"><?php echo $Web->FormatDate($Web->f('PreferredDate'), "F d Y"); ?></td>
    </tr>
    <tr>
    	<td align="left"><strong>Preferred Time:</strong></td>
        <td align="left"><?php echo $Web->FormatDate($Web->f('PreferredTime'), "h:i a"); ?></td>
    </tr>
    <tr>
      <td align="left"><strong>Purchased By: </strong></td>
      <td align="left"><?php echo $UserDetails["Name"]." | <a href='mailto:".$UserDetails["Email"]."'>".$UserDetails["Email"].'</a> | '.$Web->f('Phone'); ?></td>
    </tr>
    <tr>
      <td align="left" valign="top" style="padding-top:7px;"><strong>Shipping Address: </strong></td>
      <td align="left" valign="top"><?php echo nl2br($Web->f('Address')); ?></td>
    </tr>
    <tr>
      <td align="left" valign="top" style="padding-top:7px;"><strong>Comments: </strong></td>
      <td align="left" valign="top"><?php echo nl2br($Web->f('Comments')); ?></td>
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
<hr />
<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="60c2e9" style="border-collapse:collapse;">
<tr class="Data-Heading">
	<td width="5%" align="center"><strong>S.No</strong></td>
    <td width="15%" align="left"><strong>Type</strong></td>
    <td width="25%" align="left"><strong>Product</strong></td>
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
		if($Web2->f('InPromotion')==ACTIVE)
		{
			$Price = $Web2->f('Sale_Price');
		}
		else if($Web2->f('InWholeSale')==ACTIVE)
		{
			$Price = $Web2->f('Sale_Price');
		}
		else
		{
			$Price = $Web2->f('OriginalPrice');
		}
		$TotalPrice = $Web2->f('Quantity') * $Price;
?>
<tr>
	<td valign="top" align="center"><?php echo $Sno; ?></td>
    <td valign="top" align="left"><?php echo $ProductType[$Web2->f('Type')]; ?></td>
    <td valign="top" align="left"><?php echo $Product["Title"]; ?></td>
    <td valign="top" align="left"><?php echo $Brand["Title"]; ?></td>
    <td valign="top" align="left"><?php echo $Web2->f('Quantity'); ?></td>
    <td valign="top" align="left"><?php echo $Web->f('Currency')." ".number_format($Price); ?></td>
    <td valign="top" align="left"><?php echo $Web->f('Currency')." ".number_format($TotalPrice); ?></td>
</tr>
<?php		
	}
?>
</table>
</div>
<?php	
}
else
{
	$url = 'index.php?page=product-orders&Paging=1&';
	$page = (int) (!isset($_GET["no"]) ? 1 : $_GET["no"]);
	$limit = 50;
	$startpoint = ($page * $limit) - $limit;
	if($startpoint<0)
		$startpoint=0;
	$Limit=$startpoint.", ".$limit;
?>
<div class="Page-Data">
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
	<tr>
    	<td width="50%" align="left"><h1>Product Orders</h1></td>
        <td width="50%" align="right"><a onclick="$('#SearchBox').slideToggle();">Search</a></td>
    </tr>
</table>
<div id="SearchBox" style="display:<?php if(!isset($_REQUEST['ValidateSearch'])) { echo 'none'; }?>;">
<form name="SearchOrdersForm" id="SearchOrdersForm" method="post" action="">
<input type="hidden" name="ValidateSearch" id="ValidateSearch" value="true" />
	<table cellpadding="5" cellspacing="0" border="0" align="center">
    	<tr>
        	<td align="left">Person Name:</td>
        <td align="left"><input type="text" name="PersonName" class="Textfield" value="<?php echo $_REQUEST['PersonName']; ?>" /></td>
          <td align="left">Order Number:</td>
          <td align="left"><input type="text" name="OrderNumber" class="Textfield" value="<?php echo $_REQUEST['OrderNumber']; ?>" /></td>
        </tr>
        <tr>
        	<td align="left">Payment Mode:</td>
            <td align="left">
          <select name="PaymentModeIn" class="DropDown">
            	<option value="">Any</option>
                <?php
					foreach($PaymentMode as $Key => $Value)
					{
						$Selected='';
						if(isset($_REQUEST['PaymentMode']) && $_REQUEST['PaymentMode']==$Key)
							$Selected=' selected';
						echo '<option value="'.$Key.'"'.$Selected.'>'.$Value.'</option>';
					}
                ?>
            </select>
            </td>
          <td align="left">Order Status:</td>
            <td align="left">
          <select name="OrderStatus" class="DropDown">
            	<option value="">Any</option>
                <?php
					foreach($OrderStatus as $Key => $Value)
					{
						$Selected='';
						if(isset($_REQUEST['OrderStatus']) && $_REQUEST['PaymentMode']==$Key)
							$Selected=' selected';
						echo '<option value="'.$Key.'"'.$Selected.'>'.$Value.'</option>';
					}
                ?>
            </select>
            </td>
        </tr>
        <tr>
        	<td align="left">Order Date From:</td>
        <td align="left"><input type="text" name="OrderDateFrom" id="OrderDateFrom" class="Textfield" value="<?php echo $_REQUEST['OrderDateFrom']; ?>" /></td>
          <td align="left">Order Date To:</td>
          <td align="left"><input type="text" name="OrderDateTo" id="OrderDateTo" class="Textfield" value="<?php echo $_REQUEST['OrderDateTo']; ?>" /></td>
        </tr>
        <tr>
        	<td colspan="4" align="right"><input type="submit" name="SearchBtn" id="SearchBtn" value="Search" class="Button" /></td>
        </tr>
    </table>
</form>
</div>
<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="60c2e9" style="border-collapse:collapse;">
    <tr class="Data-Heading">
        <td width="5%" align="center">S.No</td>
        <td width="15%" align="left">User</td>
        <td width="15%" align="left">Code</td>
        <td width="15%" align="left">Date</td>
        <td width="15%" align="left">Status</td>
        <td width="15%" align="left">Payment Mode</td>
        <td width="10%" align="left">Products</td>
        <td width="10%" align="left">Total Amount</td>
        <td width="5%" align="center">Details</td>
        <td width="5%" align="center">Delete</td>
    </tr>
    <?php
		if(isset($_REQUEST['Paging']))
		{
			$Query = $_SESSION[ORDERS_QUERY];
		}
		else
		{
			$Query = "select A.*, B.Name 
			from orders A 
			inner join web_users B on B.TableID=A.UserID";
			if(isset($_REQUEST['ValidateSearch']))
			{
				$Query .= " where 1=1";
				
				if($_REQUEST['PersonName']!='')
				{
					$Query .= " and B.Name like '%".$_REQUEST['PersonName']."%'";
				}
				if($_REQUEST['OrderNumber']!='')
				{
					$Query .= " and A.OrderID like '%".$_REQUEST['OrderNumber']."%'";
				}
				if($_REQUEST['PaymentModeIn']!='')
				{
					$Query .= " and A.PaymentMode='".$_REQUEST['PaymentModeIn']."'";
				}
				if($_REQUEST['OrderStatus']!='')
				{
					$Query .= " and A.OrderStatus='".$_REQUEST['OrderStatus']."'";
				}
				if($_REQUEST['OrderDateFrom']!='')
				{
					$Query .= " and A.OrderDate>='".$_REQUEST['OrderDateFrom']."'";
				}
				if($_REQUEST['OrderDateTo']!='')
				{
					$Query .= " and A.OrderDate<='".$_REQUEST['OrderDateTo']."'";
				}
			}
			$_SESSION[ORDERS_QUERY] = $Query;
		}
		$Web->query($Query);
		$total_records = $Web->num_rows();
        if($total_records==0)
        {
    ?>
    <tr class="item">
        <td colspan="10" align="center"><div class="NoRecord" align="center">No record found</div></td>
    </tr>
    <?php		
        }
        else
        {
			$Query .= " order by A.TableID desc limit $Limit";
			$Web->query($Query);
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
    <tr id="Data_<?php echo $Web->f('TableID'); ?>">
        <td align="center"><?php echo $Sno; ?></td>
        <td align="left"><?php echo $Web->f('Name'); ?></td>
        <td align="left"><?php echo $Web->f('OrderID'); ?></td>
        <td align="left"><?php echo $Web->FormatDate($Web->f('OrderDate'), "F d Y"); ?></td>
        <td align="left"><?php echo $OrderStatus; ?></td>
        <td align="left"><?php echo $PaymentMode[$Web->f('PaymentMode')]; if($Web->f('PaymentMode')==1) { if($Web->f('PaymentDone')==ACTIVE) { echo "<br />Payment Done"; } else { echo "<br />Payment Not Done"; } } ?></td>
        <td align="left"><?php echo number_format($TotalItems); ?></td>
        <td align="left"><?php echo $Web->f('Currency')." ".number_format($GrandTotal); ?></td>
        <td align="center"><a href="index.php?page=product-orders&OrderID=<?php echo $Web->f('TableID'); ?>"><img src="images/detail.png" alt="" /></a></td>
        <td align="center"><a onclick="DeleteData('<?php echo $Web->f('TableID'); ?>', 'DeleteOrderFlag');"><img src="images/delete.png" alt="" /></a></td>
    </tr>
    <?php		
            }
	?>
    <tr>
    	<td colspan="10" align="center">
		<table cellpadding="0" cellspacing="0" border="0" align="center">
        	<tr>
            	<td align="center"><?php echo $Web->pagination($total_records, $limit, $page, $url); ?></td>
            </tr>
        </table>
		</td>
    </tr>
    <?php		
        }
    ?>
</table>
</div>
<?php
}
?>