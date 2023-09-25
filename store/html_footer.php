<script>
var WEB_URL = '<?php echo WEB_URL; ?>';
</script>
<script src="<?php echo JS_PATH; ?>modernizer.js"></script>
<script src="<?php echo JS_PATH; ?>skdslider.min.js"></script>
<script src="<?php echo JS_PATH; ?>lightbox.js"></script>
<script src="<?php echo JS_PATH; ?>jquery.carouFredSel.js"></script>
<script src="<?php echo JS_PATH; ?>viewportchecker.js"></script>
<script src="<?php echo JS_PATH; ?>selectordie.min.js"></script>
<script src="<?php echo JS_PATH; ?>responsive-table.js"></script>
<script src="<?php echo JS_PATH; ?>jquery.datetimepicker.js"></script>
<script src="<?php echo JS_PATH; ?>client.js?v=<?php echo VERSION; ?>"></script>
<?php if($DoPayment==1) { ?>
<script src="https://fast.whitepayments.com/static/white.min.js"></script>
<script src="<?php echo JS_PATH; ?>payment.js?v=<?php echo VERSION; ?>5"></script>
<?php } ?>
<script>
window.onscroll = function() {myFunction()};

var navbar = document.getElementById("TopHeader");
var nextHeader = document.getElementById("NextHeader");
var sticky = navbar.offsetTop;

function myFunction() {
  if (window.pageYOffset >= sticky) {
    navbar.classList.add("sticky")
  } else {
    navbar.classList.remove("sticky");
  }
}
</script>
</body>
</html>