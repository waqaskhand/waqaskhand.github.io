<?php
include_once("classes/config.php");
	
$MetaTitle = 'My Cart';
include_once("html_header.php");
include_once("header.php");

$Count = count($_SESSION[USER_CART]);
?>
<section class="main-content inner-page no-margin">
<div class="clear height5"></div>
    <div class="container">
        <div class="content-div">
            <div class="heading"><h2>My Cart</h2></div>
            <table id="miyazaki">
            	<thead>
                	<tr>
                        <th>Product</th>
                        <th>Size</th>
                        <th>Color</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total Price</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                <?php if($Count==0) { ?>
                	<tr>
                    	<td colspan="8" align="center" class="no-record">Cart is empty</td>
                    </tr>
                <?php } else {
						$SNo=0;
						foreach($_SESSION[USER_CART] as $Key => $Cart) {
							$SNo++;
							$Product	= $Web->getRecord($Cart["ID"], "TableID", "products");
							if($Cart["Size"]!='-1')
								$Size		= $Web->getRecord($Cart["Size"], "TableID", "p_size");
							else
								$Size["Title"]='No Size';
							if($Cart["Color"]!='-1')
								$Color		= $Web->getRecord($Cart["Color"], "TableID", "p_color");
							else
								$Color["Title"]='No Color';
							
							$Quantity	= $Cart["Quantity"];
							$ItemWeight	= $Cart["ItemWeight"];
							$TWeight	= $Quantity * $ItemWeight;
							
							$Price		= $Product["Price"];
							
							$T_Price	= $Price * $Quantity;
        		?>
                <tr id="Cart_<?php echo $Key; ?>">
                    <td><?php echo $Product["Title"]." - ".$Product["Code"]; ?></td>
                    <td><?php echo $Size["Title"]; ?></td>
                    <td><?php echo $Color["Title"]; ?></td>
                    <td>
                    <?php echo $Quantity; ?>&nbsp;<a class="cart-link" onclick="UpdateCart('p', '<?php echo $Key; ?>');">&and;</a>&nbsp;<a class="cart-link" onclick="UpdateCart('m', '<?php echo $Key; ?>');">&or;</a>
                    </td>
                    <td><?php echo DEFAULT_CURRENCY." ".number_format($Price); ?></td>
                    <td><?php echo DEFAULT_CURRENCY." ".number_format($T_Price); ?></td>
                    <td><a onclick="DeleteItem('<?php echo $Key; ?>');" class="cart-link">x</a></td>
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