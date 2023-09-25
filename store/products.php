<?php
include_once("classes/config.php");

$MetaTitle = "E-Commerce Store";

include_once("html_header.php");

include_once("header.php");



//$CheckCategory = explode("/", $Keyword);



$CheckCategory = explode("/", $_REQUEST['Keyword']);



if($CheckCategory[0]=='best-seller')

{

	$PageTitle='Best Seller';

	$Query = "select TableID, Title, Price, Image, UrlKeyword 

	from products  

	where Status='".ACTIVE."'

	and InHome='".ACTIVE."'

	order by Sequence asc";

}

else if($CheckCategory[0]=='featured-products')

{

	$PageTitle='Featured Products';

	$Query = "select TableID, Title, Price, Image, UrlKeyword 

	from products  

	where Status='".ACTIVE."'

	and InFeatured='".ACTIVE."'

	order by Sequence asc";

}

else if($CheckCategory[0]=='category')

{

	$ParentCategory = $Web->getRecord($CheckCategory[1], 'UrlKeyword', 'p_category');

	$PageTitle = $ParentCategory['Title'];

	$Query = "select TableID, Title, Price, Image, UrlKeyword 

	from products  

	where Status='".ACTIVE."'

	and (

		CategoryID in (

			SELECT TableID FROM p_category WHERE ParentID='".$ParentCategory['TableID']."'

		) or 

		CategoryID='".$ParentCategory['TableID']."'

	)

	order by Sequence asc";

}

else if($CheckCategory[0]=='sub-category')

{

	$SubCategory = $Web->getRecord($CheckCategory[1], 'UrlKeyword', 'p_category');

	$ParentCategory = $Web->getRecord($SubCategory['ParentID'], 'TableID', 'p_category');

	$PageTitle=$ParentCategory['Title']." / ".$SubCategory['Title'];

	$Query = "select TableID, Title, Price, Image, UrlKeyword 

	from products  

	where Status='".ACTIVE."'

	and CategoryID='".$SubCategory['TableID']."' 

	order by Sequence asc";

}

else if($CheckCategory[0]=='search')

{
	$pKeyword = $CheckCategory[1];

	$PageTitle = 'Product Search Results for &ldquo;'.$pKeyword.'&rdquo;';

	$Query = "select TableID, Title, Price, Image, UrlKeyword 

	from products  

	where Status='".ACTIVE."' and 

	(Title like '%$pKeyword%' or ShortDesc like '%$pKeyword%' or Description like '%$pKeyword%') 

	order by Sequence asc";

}

else

{

	$Query = "select TableID, Title, Price, Image, UrlKeyword 

	from products  

	where Status='".ACTIVE."' 

	order by Sequence asc";

}

$Web->query($Query);

$total_records = $Web->num_rows();

$total_groups = ceil($total_records/PRODUCT_ITEM_PER_GROUP);



if($PageTitle=='' || $PageTitle==' / ')

	$PageTitle='Products';

	

$_SESSION[USER_PRODUCTS_QUERY] = $Query;

?>

<section class="main-content inner-page">

<div class="container">

<?php include_once("left_categories.php"); ?>

<aside class="fleft page-content">

<section class="products-listing">

    <div class="heading"><h2><?php echo $PageTitle; ?></h2></div>

    <span id="ShowProducts"></span>

    <div id="animation_image" style="display:none;" align="center"><img src="<?php echo IMAGES_PATH; ?>loading.gif" alt="" /></div>

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

<script>

$(document).ready(function() {

	$('title').html('<?php echo $PageTitle; ?>');

    var track_load = 0; //total loaded record group(s)

    var loading  = false; //to prevents multipal ajax loads

    var total_groups = parseInt(<?php echo $total_groups; ?>) - parseInt(1); //total record group(s)

	// load first group

    $('#ShowProducts').load(WEB_URL+"show_products.php?refreshAjax="+Math.random(), 

	{'page':track_load},

	 function() {

		 track_load++; 

		 jQuery('.item').addClass('visible animated fadeIn'); 

	});

	

	//detect page scroll

	$(window).scroll(function(){

		//user scrolled to bottom of the page?

		if($(window).scrollTop() + $(window).height() == $(document).height())

        {

			if(track_load <= total_groups && loading==false)//there's more data to load

			{

				//prevent further ajax loading

				loading = true;

				//show loading image

				$('.animation_image').show();

				

				//load data from the server using a HTTP POST request

				$.post(WEB_URL+'show_products.php?refreshAjax='+Math.random(),{'group_no': track_load}, function(data){

					//append received data into the element

					$("#ShowProducts").append(data);



                    //hide loading image. hide loading image once data is received

                    $('.animation_image').hide();

					

					jQuery('.item').viewportChecker({

						classToAdd: 'visible animated fadeIn',

						offset: 100

					});

					

					//loaded group increment

					track_load++;

                    loading = false;

                }).fail(function(xhr, ajaxOptions, thrownError) { //any errors?

                    alert(thrownError); //alert with HTTP error

                    $('.animation_image').hide(); //hide loading image

                    loading = false;

                });   

            }

        }

    });

});

</script>