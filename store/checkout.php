<?php
include_once("classes/config.php");
	
if((isset($_SESSION[USER_CART]) && count($_SESSION[USER_CART])>0))
{
	$MetaTitle = 'Checkout';
	include_once("html_header.php");
	include_once("header.php");
?>
<section class="main-content inner-page contact-page no-margin">
<div class="clear height5"></div>
    <div class="container">
    <div class="heading"><h2><?php echo $MetaTitle; ?></h2></div>
    <?php if(!isset($_SESSION[WEB_USER_SESSION])) { $_SESSION['isCheckout']=1; ?>
    <p>Please first <a href="<?php echo WEB_URL; ?>login.html">login</a> or <a href="<?php echo WEB_URL; ?>register.html">register</a> to continue checkout</p>
    <?php } else { ?>
    <div class="fleft contact-form checkout-form">
    
    <form name="CheckoutForm" id="CheckoutForm" method="post" action="">
    	<input type="hidden" name="CheckoutFormFlag" id="CheckoutFormFlag" />
        <input type="hidden" name="ShipmentCharges" id="ShipmentCharges" value="0" />
        <input type="hidden" name="TotalWeight" id="TotalWeight" value="0" />
        <input type="hidden" name="DiscountCode" id="DiscountCode" value=""  />
            <div class="fleft checkout-box">Payment Mode: 
            <table cellpadding="0" cellspacing="0" border="0">
            <tr>
            <?php
            $Selected=1;
            foreach($PaymentMode as $Key => $Value)
            {
            ?>
            <td id="PaymentMode_<?php echo $Key; ?>"><label><input type="radio" name="PaymentMode" class="paymentmode" value="<?php echo $Key; ?>"<?php if($Key==$Selected) { echo ' checked="checked"'; } ?> />&nbsp;<?php echo $Value; ?></label></td>
            <td><img src="images/paypal.png" style="height:35px;width:120px;"  /></td>
            <?php	
            }
            ?>
            </tr>
            </table>
            </div>
            <div class="clear height20"></div>
                        
        <input type="text" name="Name" id="Name" class="fleft TextField" value="<?php echo $_SESSION[WEB_USER_SESSION]['Name']; ?>" disabled="disabled" required="required" placeholder="Enter your name..." />
        <input type="text" name="Email" id="Email" class="fleft TextField" value="<?php echo $_SESSION[WEB_USER_SESSION]['Email']; ?>" required="required" placeholder="Enter your email..." />
        
        <input type="text" name="Phone" id="Phone" class="fleft TextField" value="<?php echo $_SESSION[WEB_USER_SESSION]['Mobile']; ?>" required="required" placeholder="Enter your phone number..." />
        <div class="clear"></div>
        
		<input type="text" name="PreferredDate" id="PreferredDate" class="fleft TextField" required="required" placeholder="Enter your preferred date..." />
        <input type="text" name="PreferredTime" id="PreferredTime" class="fleft TextField" required="required" placeholder="Enter your preferred time..." />
          <div class="clear"></div>
          
          <textarea name="Address" id="Address" class="fleft Textarea" required="required" placeholder="Enter shipping address..."></textarea>
          <textarea name="Comments" id="Comments" class="fleft Textarea" placeholder="Enter special instructions..."></textarea>
          <div class="clear"></div>
          <!--<div align="left">
          <h3>Do you have discount code?</h3>
          <div class="checkout-box">
          <input type="text" name="DiscountCode" id="DiscountCode" class="TextField" placeholder="Enter discount code..." /><br /><span id="DiscountMsg">&nbsp;</span>
          </div>
          </div>-->
          <div align="right"><span id="CheckoutErrorMsg">&nbsp;</span>&nbsp;<input type="submit" name="SubmitBtn" id="SubmitBtn" class="Button" value="Confirm Order &rarr;" /></div>
          </form>
    </div>
    <div class="fleft contact-details checkout-summary">
    <?php include_once("order_summary.php"); ?>
    </div>      
    <?php } ?>
    <div class="clear"></div>
    </div>
</section>
<?php
	include_once("subscription.php");
	include_once("footer.php");
	include_once("html_footer.php");
}
else
	$Web->Redirect(WEB_URL);
?>