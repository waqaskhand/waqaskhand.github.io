<div class="Page-Data">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
    	<td align="left" width="80%"><h1>Brands</h1></td>
        <td align="right" width="20%"><a href="index.php?page=add-brand">Add New Brand</a></td>
    </tr>
</table>
<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="60c2e9" style="border-collapse:collapse;">
	<tr class="Data-Heading">
	  <td width="5%" align="center">S.No</td>
        <td width="70%" align="left">Brand</td>
        <td width="10%" align="center">Image</td>
        <td width="5%" align="center">Active</td>
        <td width="5%" align="center">Edit</td>
        <td width="5%" align="center">Delete</td>
    </tr>
    <?php
		$Web->query("select * from p_brands order by Title asc");
		if($Web->num_rows()>0)
		{
			$Sno=0;
			while($Web->next_Record())
			{
				$Sno++;
	?>
    <tr id="Data_<?php echo $Web->f('TableID'); ?>">
      <td align="center"><?php echo $Sno; ?></td>
        <td align="left"><?php echo $Web->f('Title'); ?></td>
        <td align="center"><table cellpadding="0" cellspacing="0" border="0" align="center">
        	<tr>
			<?php if($Web->f('Image')!='') { ?>
            <td id="ImageView_<?php echo $Web->f('TableID'); ?>"><a target="_blank" href="../<?php echo IMAGES_FOLDER."/".$Web->f('Image'); ?>"><img src="images/image_icon.jpg" alt="" /></a></td>
            <td id="ImageDelete_<?php echo $Web->f('TableID'); ?>"><a onclick="DeleteImage('<?php echo $Web->f('TableID'); ?>', 'p_brands', 'Image');"><img src="images/delete.png" alt="" hspace="5" /></a></td>
            <?php } ?>
            <td align="center"><a onclick="UploadImage('<?php echo BRAND_LOGO_IMAGE_WIDTH; ?>', '<?php echo BRAND_LOGO_IMAGE_HEIGHT; ?>', '<?php echo IMAGES_FOLDER; ?>', '<?php echo IMAGE_TYPE_NORMAL; ?>', '<?php echo $Web->f('TableID'); ?>', 'p_brands', 'Image');"><img src="images/upload_icon.jpg" alt="" /></a></td>
            </tr>
        </table></td>
        <td align="center"><?php echo $ActiveInactive[$Web->f('Status')]; ?></td>
        <td align="center"><a href="index.php?page=add-brand&PageType=Edit&ID=<?php echo $Web->f('TableID'); ?>"><img src="images/edit.png" alt="" /></a></td>
        <td align="center"><a onclick="DeleteData('<?php echo $Web->f('TableID'); ?>', 'ValidateBrandFlag');"><img src="images/delete.png" alt="" /></a></td>
    </tr>
    <?php			
			}
		}
		else
		{
			echo '<tr><td colspan="6" align="center"><div class="NoRecord" align="center">No record found</div></td></tr>';
		}
	?>
</table>
</div>