<?php
include_once("classes/config.php");
$MetaTitle = "E-Commerce Store";

include_once("html_header.php");

include_once("header.php");

include_once("banner.php");

?>

<section class="main-content">

<div class="container">

<?php include_once("left_categories.php"); ?>

<aside class="fleft page-content">

<section class="products-listing">

    <div class="heading"><h2>&nbsp Trending Products</h2></div>

    <?php

		$Query = "select TableID, Title, Price, Image, Stock, UrlKeyword 

		from products  

		where Status='".ACTIVE."'

		and InFeatured='".ACTIVE."'

		order by Sequence asc";

		$Products->ShowProducts($Query);

	?>

</section>



<section class="products-listing">

    <div class="heading"><h2>&nbsp Just For You</h2></div>

    <?php

		$Query = "select TableID, Title, Price, Image, Stock, UrlKeyword 

		from products  

		where Status='".ACTIVE."'

		and InHome='".ACTIVE."'

		order by Sequence asc";

		$Products->ShowProducts($Query);

	?>

</section>

</aside>

<div class="clear"></div>

</div>

</section>

<?php

	include_once("subscription.php");

	include_once("footer.php");

	include_once("html_footer.php");

?>


