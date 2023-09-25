<?php
include_once("classes/config.php");
if(isset($_SESSION['DoPayment']))
{
	$MetaTitle = 'Make Payment';
	include_once("html_header.php");
	include_once("header.php");
?>
<section class="main-content inner-page no-margin">
	<div class="clear height5"></div>
    <div class="container">
        <div class="content-div">
            <div class="heading"><h2><?php echo $MetaTitle; ?></h2></div>
            <div class="CardForm">
            	<h2>Amount to Pay: <?php echo DEFAULT_CURRENCY." ".number_format($_SESSION['DoPayment']['AmountToPay']); ?></h2>
                <img src="images/pleasewait.gif"   />
                
            <?php /*?><form name="PaymentForm" id="PaymentForm" method="post" action="">
            	<input type="hidden" name="Amount" id="Amount" value="<?php echo $_SESSION['DoPayment']['AmountToPay']; ?>" />
            	Name on Card:<br />
                <input type="text" name="CardName" id="CardName" class="TextField" required />
                <div class="clear height20"></div>
                Card Number:<br />
            	<input type="text" name="Card" id="Card" class="TextField" required />
                <div class="clear height20"></div>
                Expiry Month:<br />
                <select name="ExpMonth" id="ExpMonth" required>
                <?php for($expm=1; $expm<=12; $expm++) { ?>
                	<option value="<?php echo $expm; ?>"><?php echo $expm; ?></option>
                <?php } ?>    
                </select>
                <div class="clear height20"></div>
                Expiry Year:<br />
                <input type="number" name="ExpYear" id="ExpYear" class="TextField" maxlength="4" required />
                <div class="clear height20"></div>
                CVC Code:<br />
                <input type="number" name="CVCCode" id="CVCCode" class="TextField" maxlength="3" required />
                <div class="clear height20"></div>
                <input type="submit" name="PaymentBtn" id="PaymentBtn" class="Button" value="Pay Now" />&nbsp;
            </form><?php */?>
            <!--https://www.sandbox.paypal.com/cgi-bin/webscr
            alishahkhan-facilitator@gmail.com-->
            
            <form name="checkout_confirmation"  id="frmpaypal"  action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="helen@lpaircon.co.uk">
<input type="hidden" name="amount" value="<?php echo number_format($_SESSION['DoPayment']['AmountToPay']);?>">
<input type="hidden" name="item_name" value="LP AIR CON Deal">
<input type="hidden" name="currency_code" value="GBP">
<input type="hidden" name="custom" value="<?php echo $_SESSION['DoPayment']['OrderNumber'];?>">
<input type="hidden" name="notify_url" value="<?php echo $_SESSION['DoPayment']['NotifyURL'];?>">
<input type="hidden" name="return" value="<?php echo $_SESSION['DoPayment']['ReturnURL'];?>">
<input type="hidden" name="cancel_return" value="<?php echo WEB_URL;?>">
<input type="hidden" name="paymentaction" value="sale">
</form>

            </div>
            <div id="PaymentReturnMsg">&nbsp;</div>
        </div>
    </div>
</section>

<?php
	include_once("subscription.php");
	include_once("footer.php");
	$DoPayment=1;
	include_once("html_footer.php");
	?>
    <script language="javascript">
frmpaypal.submit();
</script>
    <?php
}
else
	$Web->Redirect(WEB_URL);
?>