<?php

$Facebook_Link	= $_SESSION[WEBSITE_SETTINGS][3];

$Facebook_Target=' target="_blank"';

if($Facebook_Link=='')

{

	$Facebook_Link='#';

	$Facebook_Target='';

}



$Twitter_Link	= $_SESSION[WEBSITE_SETTINGS][4];

$Twitter_Target=' target="_blank"';

if($Twitter_Link=='')

{

	$Twitter_Link='#';

	$Twitter_Target='';

}



$Google_Link	= $_SESSION[WEBSITE_SETTINGS][5];

$Google_Target=' target="_blank"';

if($Google_Link=='')

{

	$Google_Link='#';

	$Google_Target='';

}



$Pinterest_Link	= $_SESSION[WEBSITE_SETTINGS][9];

$Pinterest_Target=' target="_blank"';

if($Pinterest_Link=='')

{

	$Pinterest_Link='#';

	$Pinterest_Target='';

}



$Instagram_Link	= $_SESSION[WEBSITE_SETTINGS][10];

$Instagram_Target=' target="_blank"';

if($Instagram_Link=='')

{

	$Instagram_Link='#';

	$Instagram_Target='';

}



$Linkedin_Link	= $_SESSION[WEBSITE_SETTINGS][11];

$Linkedin_Target=' target="_blank"';

if($Linkedin_Link=='')

{

	$Linkedin_Link='#';

	$Linkedin_Target='';

}



$CountCart = $Products->CountCart();

$Web->query("select * from p_category 

where Status='".ACTIVE."' and 

ParentID=0 

order by Sequence asc");

$pC=0;

while($Web->next_Record())

{

	$pC++;

	$Link = WEB_URL."products/category/".$Web->f('UrlKeyword').".html";

	$pCategories[$pC] = array($Web->f('TableID'), $Web->f('Title'), $Link);

}

$TotalpCat=count($pCategories);
if(isset($_POST['search'])){
	$text = str_replace(' ', '%20', $_POST['searchProducts']);
	$Web->Redirect(WEB_URL."products/search/".$text.".html");
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<section class="top-header hidden" id="TopHeader">

	<div class="container">

    	<div class="fleft social-media">

        	<ul>

            	<li><a href="<?php echo $Facebook_Link; ?>"<?php echo $Facebook_Target; ?> class="facebook"></a></li>
                
                <li><a href="<?php echo $Instagram_Link; ?>"<?php echo $Instagram_Target; ?> class="instagram"></a></li>

                <?php /*?><li><a href="<?php echo $Linkedin_Link; ?>"<?php echo $Linkedin_Target; ?> class="linkedin"></a></li>

                <li><a href="<?php echo $Google_Link; ?>"<?php echo $Google_Target; ?> class="googleplus"></a></li>

                <li><a href="<?php echo $Pinterest_Link; ?>"<?php echo $Pinterest_Target; ?> class="pinterest"></a></li> <?php */?>               

				<li><a href="<?php echo $Twitter_Link; ?>"<?php echo $Twitter_Target; ?> class="twitter"></a></li>

            </ul>

            <div class="clear"></div>

        </div>
		<form method="POST">
		<div class="fleft top-search">
			<input type="text" name="searchProducts" placeholder="Search in store" class="form-control headerSearch" />
			<button type="submit" name="search" class="SearchButton"><i class="fa fa-search"></i></button>	
		</div>
		
		</form>
        <div class="fright top-links">

        	<ul>
			<li><a href="<?php echo WEB_URL; ?>">Home</a></li>

            <?php if(isset($_SESSION[WEB_USER_SESSION])) { ?>

            	<li class="welcome">Welcome <?php echo $_SESSION[WEB_USER_SESSION]['Name']; ?></li>

                <li><a href="<?php echo WEB_URL; ?>my-orders.html">My Orders</a></li>

                <li><a href="<?php echo WEB_URL; ?>logout.php">Logout</a></li>

            <?php } else { ?>

            	<li><a href="<?php echo WEB_URL; ?>login.html">Login</a></li>

                <li><a href="<?php echo WEB_URL; ?>register.html">Register</a></li>

            <?php } ?>    

                <li class="my-cart"><a href="<?php echo WEB_URL; ?>my-cart.html">My Cart (<span id="CartCount"><?php echo $CountCart; ?></span>)</a></li>

            </ul>

        </div>

        <div class="clear"></div>

    </div>

</section>



<section class="header">

	<div class="container">

    	<div class="fleft logo hidden"><a href="<?php echo WEB_URL; ?>"><img src="<?php echo IMAGES_PATH; ?>logo.png" alt="" /></a></div>

        <div class="fright main-categories hidden">

        	<ul>

            <?php

            	for($a=1; $a<=3; $a++)

				{

			?>
										
            	<li><a href="<?php echo $pCategories[$a][2]; ?>" class="<?php echo $mainCatClass[$a]; ?>"><?php echo $pCategories[$a][1]; ?></a></li>

            <?php

				}

			?>	

            </ul>

            <div class="clear"></div>

        </div>

        <div class="clear"></div>

    </div>

</section>

