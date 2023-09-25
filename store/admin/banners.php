<div class="Page-Data">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
    	<td align="left" width="75%"><h1>Banners</h1></td>
        <td align="right" width="25%"><a onclick="UploadImageWOCrop('<?php echo BANNER_WIDTH; ?>', '<?php echo BANNER_HEIGHT; ?>', '<?php echo IMAGES_FOLDER; ?>', '<?php echo IMAGE_TYPE_BANNER; ?>', 0, '', '');">Upload Banner</a></td>
    </tr>
</table>
<form name="RecordForm" id="RecordForm" method="post" action="">
<input type="hidden" name="ValidateBannerFlag" id="ValidateBannerFlag" value="true" />
<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="60c2e9" style="border-collapse:collapse;">
	<tr class="Data-Heading">
	  <td width="5%" align="left">S.No</td>
    	<td width="45%" align="left">Image</td>
    	<td width="45%" align="left">Link it with category</td>
    	<td width="5%" align="center">Delete</td>
    </tr>
    <?php
		$Web->query("select * from web_images where Type='".IMAGE_TYPE_BANNER."' order by TableID asc");
		if($Web->num_rows()>0)
		{
			while($Web->next_Record())
			{
	?>
    <tr id="Data_<?php echo $Web->f('TableID'); ?>">
      <td align="left">&nbsp;</td>
    	<td align="left"><img src="../<?php echo IMAGES_FOLDER."/".$Web->f('FileName'); ?>" alt="" width="300" /></td>
    	<td align="left">
        <input type="hidden" name="RecordIDs[]" value="<?php echo $Web->f('TableID'); ?>" />
        <select name="CategoryIDs[]" class="DropDown">
	      <option value="">Please Select</option>
	      <?php
				$Web2->query("select * from p_category where Status='".ACTIVE."' and ParentID='0' order by Sequence asc");
				if($Web2->num_rows()>0)
				{
					while($Web2->next_Record())
					{
						$pCat = $Web2->f('TableID');
						$Selected='';
						if($pCat==$Web->f('TitleOne'))
							$Selected=' selected';
			?>
	      <option value="<?php echo $pCat; ?>"<?php echo $Selected; ?>><?php echo $Web2->f('Title'); ?></option>
	      <?php
				$Web3->query("select * from p_category where Status='".ACTIVE."' and ParentID='$pCat' order by Sequence asc");
				if($Web3->num_rows()>0)
				{
					while($Web3->next_Record())
					{
						$Selected='';
						if($Web3->f('TableID')==$Web->f('TitleOne'))
							$Selected=' selected';
			?>
	      <option value="<?php echo $Web3->f('TableID'); ?>"<?php echo $Selected; ?>>&nbsp;--&nbsp;<?php echo $Web3->f('Title'); ?></option>
	      <?php			
					}
				}
			?>
	      </optgroup>
	      <?php			
					}
				}
			?>
	      </select></td>
    	<td align="center"><a onclick="DeleteData('<?php echo $Web->f('TableID'); ?>', 'ValidateContentFlag');"><img src="images/delete.png" alt="" /></a></td>
    </tr>
    <?php			
			}
	?>
    <tr>
    	<td colspan="4" align="right"><input type="submit" name="SubmitBtn" id="SubmitBtn" value="Submit" class="Button" /></td>
    </tr>
    <?php		
		}
		else
		{
			echo '<tr><td colspan="4" align="center"><div class="NoRecord" align="center">No record found</div></td></tr>';
		}
	?>
</table>
</form>
</div>