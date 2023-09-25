<?php
if($_REQUEST['PageType']=='images' && $_REQUEST['RecordID']!='')
{
	$RecordID = $_REQUEST['RecordID'];
	$Content = $Web->getRecord($RecordID, "TableID", "products");
	if($Content["TableID"]=='')
		$Web->Redirect("index.php?page=products");
?>
<div class="Page-Data">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
    	<td align="left" width="75%"><h1><?php echo $Content["Title"]; ?> Images</h1></td>
        <td align="right" width="25%"><a onclick="UploadImageWOCrop('<?php echo PRODUCT_IMAGE_GALLERY_WIDTH; ?>', '<?php echo PRODUCT_IMAGE_GALLERY_HEIGHT; ?>', '<?php echo IMAGES_FOLDER; ?>', '<?php echo IMAGE_TYPE_PRODUCT_GALLERY; ?>', '<?php echo $RecordID; ?>', '', '');">Upload Image</a></td>
    </tr>
</table>
<form name="UpdateSequenceForm" id="UpdateSequenceForm" method="post" action="">
<input type="hidden" name="UpdateSequenceFlag" id="UpdateSequenceFlag" value="true" />
<input type="hidden" name="TableName" id="TableName" value="web_images" />
<input type="hidden" name="returnPage" id="returnPage" value="products&PageType=images&RecordID=<?php echo $RecordID; ?>" />
<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="60c2e9" style="border-collapse:collapse;">
	<tr class="Data-Heading">
    	<td width="80%" align="left">Image</td>
    	<td width="15%" align="center">Sequence</td>
        <td width="5%" align="center">Delete</td>
    </tr>
    <?php
		$Web->query("select * from web_images 
		where 
		Type='".IMAGE_TYPE_PRODUCT_GALLERY."' and 
		ParentID='$RecordID' 
		order by Sequence asc");
		if($Web->num_rows()>0)
		{
			while($Web->next_Record())
			{
	?>
    <tr id="Data_<?php echo $Web->f('TableID'); ?>">
    	<td align="left"><img src="../<?php echo IMAGES_FOLDER."/".$Web->f('FileName'); ?>" alt="" height="300" /></td>
    	<td align="center"><input type="hidden" name="RecordIDs[]" value="<?php echo $Web->f('TableID'); ?>" /><input type="text" name="Sequence[]" value="<?php echo $Web->f('Sequence'); ?>" style="width:50px;" /></td>
        <td align="center"><a onclick="DeleteData('<?php echo $Web->f('TableID'); ?>', 'ValidateContentFlag');"><img src="images/delete.png" alt="" /></a></td>
    </tr>
    <?php			
			}
	?>
    <tr>
    	<td colspan="3" align="right"><input type="submit" name="SubmitBtn" id="SubmitBtn" value="Update Sequence" class="Button" /></td>
    </tr>
    <?php		
		}
		else
		{
			echo '<tr><td colspan="3" align="center"><div class="NoRecord" align="center">No images found</div></td></tr>';
		}
	?>
</table>
</form>
</div>
<?php
}
else
{
	$url = 'index.php?page=products&Paging=1&';
	$page = (int) (!isset($_GET["no"]) ? 1 : $_GET["no"]);
	$limit = 50;
	$startpoint = ($page * $limit) - $limit;
	if($startpoint<0)
		$startpoint=0;
	$Limit=$startpoint.", ".$limit;
?>
<div class="Page-Data">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
    	<td align="left" width="80%"><h1>Products</h1></td>
        <td align="right" width="20%"><a href="index.php?page=add-product">Add New Product</a></td>
    </tr>
</table>
<form name="UpdateSequenceForm" id="UpdateSequenceForm" method="post" action="">
<input type="hidden" name="UpdateSequenceFlag" id="UpdateSequenceFlag" value="true" />
<input type="hidden" name="TableName" id="TableName" value="products" />
<input type="hidden" name="returnPage" id="returnPage" value="products" />
<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="60c2e9" style="border-collapse:collapse;">
    <tr class="Data-Heading">
      <td width="5%" align="center">S.No</td>
        <td width="30%" align="left">Product</td>
        <td width="10%" align="left">Price</td>
        <td width="10%" align="center">Just For You</td>
        <td width="5%" align="center">Sequence</td>
        <td width="10%" align="center">Image</td>
        <td width="5%" align="center">Images</td>
        <td width="5%" align="center">Active</td>
        <td width="5%" align="center">Edit</td>
        <td width="5%" align="center">Delete</td>
    </tr>
    <?php
		if(isset($_REQUEST['Paging']))
		{
			$Query = $_SESSION[PRODUCTS_QUERY];
		}
		else
		{
			$Query = "select * from products";
			$_SESSION[PRODUCTS_QUERY] = $Query;
		}
		$Web->query($Query);
		$total_records = $Web->num_rows();
		if($total_records>0)
		{
			$Query .= " order by CategoryID asc, Sequence asc limit $Limit";
			$Web->query($Query);
			$Sno=0;
			while($Web->next_Record())
			{
				$Sno++;
				$SubCategoryDetail = $Web2->getRecord($Web->f('CategoryID'), "TableID", "p_category");
				if($SubCategoryDetail["ParentID"]!='0')
				{
					$ParentCategoryDetail = $Web2->getRecord($SubCategoryDetail["ParentID"], "TableID", "p_category");
					$Category = $ParentCategoryDetail["Title"]." / ".$SubCategoryDetail["Title"];
				}
				else
				{
					$Category = $SubCategoryDetail["Title"];
				}
				if($Category!=$LastCategory)
				{
					$LastCategory=$Category;
	?>
    <tr>
    	<td colspan="10" align="left"><strong><?php echo $Category; ?></strong></td>
    </tr>
    <?php				
				}
	?>
    <tr id="Data_<?php echo $Web->f('TableID'); ?>">
      <td align="center" valign="top"><?php echo $Sno; ?></td>
        <td align="left" valign="top"><?php echo $Web->f('Title')." - ".$Web->f('Code'); ?></td>
        <td align="left" valign="top"><?php echo DEFAULT_CURRENCY." ".$Web->NumberFormat($Web->f('Price')); ?></td>
        <td align="center" valign="top"><?php echo $ActiveInactive[$Web->f('InHome')]; ?></td>
        <td align="center" valign="top"><input type="hidden" name="RecordIDs[]" value="<?php echo $Web->f('TableID'); ?>" /><input type="text" name="Sequence[]" value="<?php echo $Web->f('Sequence'); ?>" style="width:50px;" /></td>
        <td align="center" valign="top">
        <table cellpadding="0" cellspacing="0" border="0" align="center">
        	<tr>
			<?php if($Web->f('Image')!='') { ?>
            <td id="ImageView_<?php echo $Web->f('TableID'); ?>"><a target="_blank" href="../<?php echo IMAGES_FOLDER."/".$Web->f('Image'); ?>"><img src="images/image_icon.jpg" alt="" /></a></td>
            <td id="ImageDelete_<?php echo $Web->f('TableID'); ?>"><a onclick="DeleteImage('<?php echo $Web->f('TableID'); ?>', 'products', 'Image');"><img src="images/delete.png" alt="" hspace="5" /></a></td>
            <?php } ?>
            <td align="center"><a onclick="UploadImage('<?php echo PRODUCT_HOME_IMAGE_WIDTH; ?>', '<?php echo PRODUCT_HOME_IMAGE_HEIGHT; ?>', '<?php echo IMAGES_FOLDER; ?>', '<?php echo IMAGE_TYPE_NORMAL; ?>', '<?php echo $Web->f('TableID'); ?>', 'products', 'Image');"><img src="images/upload_icon.jpg" alt="" /></a></td>
            </tr>
        </table>
        </td>
        <td align="center" valign="top"><a href="index.php?page=products&PageType=images&RecordID=<?php echo $Web->f('TableID'); ?>"><img src="images/image_icon.jpg" alt="" /></a></td>
        <td align="center" valign="top"><?php echo $ActiveInactive[$Web->f('Status')]; ?></td>
        <td align="center" valign="top"><a href="index.php?page=add-product&PageType=Edit&ID=<?php echo $Web->f('TableID'); ?>"><img src="images/edit.png" alt="" /></a></td>
        <td align="center" valign="top"><a onclick="DeleteData('<?php echo $Web->f('TableID'); ?>', 'ValidateProductFlag');"><img src="images/delete.png" alt="" /></a></td>
    </tr>
    <?php
			}
	?>
    <tr>
    	<td colspan="10" align="right">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
        	<tr>
            	<td width="85%" align="left"><?php echo $Web->pagination($total_records, $limit, $page, $url); ?></td>
                <td width="15%" align="right"><input type="submit" name="SubmitBtn" id="SubmitBtn" value="Update Sequence" class="Button" /></td>
            </tr>
        </table>
		</td>
    </tr>
    <?php		
		}
		else
		{
			echo '<tr><td colspan="11" align="center"><div class="NoRecord" align="center">No record found</div></td></tr>';
		}
	?>
</table>
</form>
</div>
<?php
}
?>