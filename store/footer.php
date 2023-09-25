<footer>

	<div class="container">

    	<div class="fleft footer-box">

        	<h2><a href="#">E-Commerce Store</a></h2>

        	<ul>

            	<li><a href="<?php echo WEB_URL; ?>page/about-us.html">About Us</a></li>

                <li><a href="<?php echo WEB_URL; ?>page/terms-and-conditions.html">Terms &amp; Conditions</a></li>

                <li><a href="<?php echo WEB_URL; ?>page/privacy-policy.html">Privacy Policy</a></li>

                <li><a href="<?php echo WEB_URL; ?>contact-us.html">Contact Us</a></li>

            </ul>

        </div>

        <div class="fleft footer-box footer-my-account">

        	<h2>My Account</h2>

        	<ul>

            <?php if(isset($_SESSION[WEB_USER_SESSION])) { ?>

            <li><a href="<?php echo WEB_URL; ?>my-orders.html">My Orders</a></li>

            <li><a href="<?php echo WEB_URL; ?>change-password.html">Change Password</a></li>

            <?php } else { ?>

            	<li><a href="<?php echo WEB_URL; ?>login.html">Login</a></li>

                <li><a href="<?php echo WEB_URL; ?>register.html">Register</a></li>

            <?php } ?>    

                <li><a href="<?php echo WEB_URL; ?>my-cart.html">Checkout</a></li>

            </ul>

        </div>

        <div class="fleft footer-box footer-join-us">

        	<h2>Join Us</h2>

            <ul>

            	<li>

                	<div class="social-media">

                    	<ul>

                            <li><a href="<?php echo $Facebook_Link; ?>"<?php echo $Facebook_Target; ?> class="facebook"></a></li>

                            <li><a href="<?php echo $Instagram_Link; ?>"<?php echo $Instagram_Target; ?> class="instagram"></a></li>

                            <li><a href="<?php echo $Twitter_Link; ?>"<?php echo $Twitter_Target; ?> class="twitter"></a></li>

                            <!-- <li><a href="<?php echo $Pinterest_Link; ?>"<?php echo $Pinterest_Target; ?> class="pinterest"></a></li>-->

                            <!-- <li><a href="<?php echo $Linkedin_Link; ?>"<?php echo $Linkedin_Target; ?> class="linkedin"></a></li> -->

                        </ul>

                        <div class="clear"></div>

                    </div>

                </li>

                <li><a href="mailto:<?php echo $_SESSION[WEBSITE_SETTINGS][2]; ?>"><?php echo $_SESSION[WEBSITE_SETTINGS][2]; ?></a></li>

                <li class="phone"><?php echo $_SESSION[WEBSITE_SETTINGS][1]; ?></li>

            </ul>

        </div>

        <div class="fleft footer-box">

        	<h2>We Accept</h2>

            <img src="images/payment.png" alt="" class="payment" />

        </div>

        <div class="clear"></div>

    </div>

    <section class="copyright">

    <div class="container">

        <div class="fleft">&copy; Copyright <?php echo date('Y'); ?>. All rights reserved</div>

        <div class="fright"><a href="http://acubedsystems.co.za/" title="Sanam Web Design" target="_blank"> E-Commerce Store </a> by <a href="http://acubedsystems.co.za/" title="Sanam Web Design" target="_blank">Waqas Khan</a></div>

        <div class="clear"></div>

    </div>

    </section>

</footer>
<script src="https://static.elfsight.com/platform/platform.js" data-use-service-core defer></script>
<div class="elfsight-app-8a753ccc-6a5b-4b3f-9fca-0f12b3727baa"></div>


