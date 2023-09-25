<?php

include_once("classes/config.php");

$MetaTitle = "E-Commerce Store";



include_once("html_header.php");



include_once("header.php");



$ProductDetail = $Web->getRecord($_REQUEST['Keyword'], 'UrlKeyword', 'products');



if($ProductDetail['TableID']=='' || $ProductDetail['Status']==INACTIVE)

	$Web->Redirect(WEB_URL);

	

$ProductID = $ProductDetail['TableID'];

$ProductDescription = $Web->FilterDescription($ProductDetail['Description']);



$SubCategory = $Web->getRecord($ProductDetail['CategoryID'], 'TableID', 'p_category');

if($SubCategory['ParentID']!=0)

{

	$ParentCategory = $Web->getRecord($SubCategory['ParentID'], 'TableID', 'p_category');

	$PageTitle = $ParentCategory["Title"]." / ".$SubCategory["Title"];

}

else

{

	$PageTitle = $SubCategory["Title"];

}



$Web->query("select * from web_images 

where Type='".IMAGE_TYPE_PRODUCT_GALLERY."' 

and ParentID='$ProductID' 

order by Sequence asc");



$CountPhotos = $Web->num_rows();

if($CountPhotos>0)

{

	$Images = array();

	while($Web->next_Record())

	{

		$Images[] = WEB_URL.IMAGES_FOLDER."/".$Web->f('FileName');

	}

}

$Stock=10;

?>

<section class="main-content inner-page">

<div class="container">

<?php include_once("left_categories.php"); ?>

<aside class="fleft page-content">

<section class="products-listing">

    <div class="heading"><h2><?php echo $PageTitle; ?></h2></div>

    <div class="pDetailpage">

    	<div class="fleft product-detail">

        	<h2><?php echo $ProductDetail['Title']; ?></h2>
            <h2><?php echo DEFAULT_CURRENCY." ".number_format($ProductDetail['Price'],2);; ?></h2>
            

            <p><?php echo $ProductDetail['ShortDesc']; ?></p>
			<?php $brand = $Web->getRecord($ProductDetail['BrandID'], 'TableID', 'p_brands');
				$Web->query("SELECT Title FROM `p_brands` WHERE `TableID` = '".$ProductDetail['BrandID']."'");
				 if($Web->num_rows()>0) { ?>
						<p style="color: #F79019"><?php echo $brand['Title']; ?></p>
					<?php } ?>
            

            <form name="AddToCartForm" id="AddToCartForm" method="post" action="">

            	<input type="hidden" name="AddToCartFlag" id="AddToCartFlag" value="" />

                <input type="hidden" name="ProductID" id="ProductID" value="<?php echo $ProductID; ?>" />

                <div class="available">
                	<?php if($ProductDetail['Stock']>0){ ?>
                <ul>

                <?php

					$Web->query("select B.TableID, B.Title, B.ColorCode 

					from product_color A 

					inner join p_color B on B.TableID=A.ColorID 

					where A.ProductID='$ProductID' 

					and B.Status='".ACTIVE."'");

					if($Web->num_rows()>0)

					{

				?>

				<li class="color-box">

				<select name="Color" id="Color">

                	<option value="">Select Color</option>

					<?php while($Web->next_Record()) { ?>

						<option value="<?php echo $Web->f('TableID'); ?>" data-imagesrc="<?php echo WEB_URL; ?>colorimg.php?c=<?php echo $Web->f('ColorCode'); ?>"><?php echo $Web->f('Title'); ?></option>

					<?php } ?>    

				</select>

				</li>

				<?php

					}

					else

					{ echo '<input type="hidden" name="Color" value="-1" />'; }

					

					$Web->query("select B.TableID, B.Title 

					from product_size A 

					inner join p_size B on B.TableID=A.SizeID 

					where A.ProductID='$ProductID' 

					and B.Status='".ACTIVE."'");

					

					if($Web->num_rows()>0)

					{

				?>

                <li class="size-box">

                    <select name="Size" id="Size">

                    <option value="">Select Size</option>

                        <?php while($Web->next_Record()) { ?>

                            <option value="<?php echo $Web->f('TableID'); ?>"><?php echo $Web->f('Title'); ?></option>

                        <?php } ?>

                    </select>

                </li>

                <?php		

					}

					else

					{ echo '<input type="hidden" name="Size" value="-1" />'; }

				?>

                <li class="quantity-box">

                <select name="Quantity" id="Quantity">

                <option value="">Select Quantity</option>

                    <?php for($a=1; $a<=$Stock; $a++) { ?>

                        <option value="<?php echo $a; ?>"><?php echo $a; ?></option>

                    <?php } ?>

                </select>

                </li>

                <li><input type="submit" name="AddtoCartBtn" id="AddtoCartBtn" value="Add to Cart" class="Button" /></li>

                </ul>
            <?php } else { ?> 
            	<p>This Product is out Of Stock</p>
            <?php } ?>

                <span id="CartMsg">&nbsp;</span>

                </div>

            </form>

    	</div>

    	<div class="fleft product-images">

        <?php

			if($CountPhotos>0)

			{

				$MainImage = WEB_URL."thumb.php?src=".$Images[0]."&w=430";

				echo '<img src="'.$MainImage.'" alt="" class="main-image" />';

				echo '<a id="pi-prev"></a><a id="pi-next"></a>';

				echo '<ul class="pImagesList">';

					foreach($Images as $Image) {

						$ThumbImage = WEB_URL."thumb.php?src=".$Image."&w=120";

						echo '<li><a data-lightbox="gallery" href="'.$Image.'"><img src="'.$ThumbImage.'" alt="" /></a></li>';

					}

				echo '</ul>';

			}

		?>

        </div>

        <div class="clear"></div>

        

        <div class="product-description">

        	<h2>More Description</h2>

            <div class="CmsDescription"><?php echo $ProductDescription; ?></div>

        </div>

    </div>

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