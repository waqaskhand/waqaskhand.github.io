<?php
$RecordID='';
$Content["Status"]=ACTIVE;
$ReadOnly='';
$Title='Add New Product';
if(isset($_REQUEST['PageType']) && isset($_REQUEST['ID']) && $_REQUEST['PageType']=='Edit' && $_REQUEST['ID']!='')
{
	$RecordID = $_REQUEST['ID'];
	$Content = $Web->getRecord($_REQUEST['ID'], "TableID", "products");
	if($Content["TableID"]=='')
		$Web->Redirect("index.php?page=products");
		
	 $ReadOnly=' readonly="readonly"';
	 $Title='Edit Product';
}
?>
<div class="Page-Data">
<h1><?php echo $Title; ?></h1>
<form name="RecordForm" id="RecordForm" method="post" action="" onsubmit="return ValidateProduct();" enctype="multipart/form-data">
<input type="hidden" name="ValidateProductFlag" id="ValidateProductFlag" />
<input type="hidden" name="RecordID" id="RecordID" value="<?php echo $RecordID; ?>" />
<input type="hidden" name="Stock" id="Stock" value="100" />
<table width="100%" cellpadding="5" cellspacing="0" border="0">
	<tr>
	  <td align="left">Brand</td>
	  <td align="left"><select name="BrandID" id="BrandID" class="DropDown">
        	<option value="">Please Select</option>
            <?php echo $Web->getSelectDropDownWhereField($Content["BrandID"], "TableID", "Title", "Status", ACTIVE, "p_brands", "Title"); ?>
        </select></td>
	  </tr>
	<tr>
	  <td align="left">Category</td>
	  <td align="left">
	    <select name="CategoryID" id="CategoryID" class="DropDown">
	      <option value="">Please Select</option>
	      <?php
				$Web->query("select * from p_category where Status='".ACTIVE."' and ParentID='0' order by Sequence asc");
				if($Web->num_rows()>0)
				{
					while($Web->next_Record())
					{
			?>
	      <optgroup label="<?php echo $Web->f('Title'); ?>">
	        <?php
				$Web2->query("select * from p_category where Status='".ACTIVE."' and ParentID='".$Web->f('TableID')."' order by Sequence asc");
				if($Web2->num_rows()>0)
				{
					while($Web2->next_Record())
					{
						$Selected='';
						if($Web2->f('TableID')==$Content["CategoryID"])
							$Selected=' selected';
			?>
	        <option value="<?php echo $Web2->f('TableID'); ?>"<?php echo $Selected; ?>><?php echo $Web2->f('Title'); ?></option>
	        <?php			
					}
				}
			?>
	        </optgroup>
	      <?php			
					}
				}
			?>
	      </select>
	    </td>
	  </tr>
	<tr>
    	<td width="25%" align="left">Title</td>
        <td width="75%" align="left"><input type="text" name="Title" id="Title" class="Textfield" value="<?php echo $Content["Title"]; ?>" /></td>
    </tr>
	<tr>
	  <td align="left" valign="top" style="padding-top:7px;">Short Description</td>
	  <td align="left" valign="top" style="padding-top:7px;">
      	<textarea name="ShortDesc" id="ShortDesc" class="TextArea"><?php echo $Content['ShortDesc']; ?></textarea>
      </td>
	  </tr>
	<tr>
	  <td align="left">Product Code</td>
	  <td align="left"><input type="text" name="Code" id="Code" class="Textfield" value="<?php echo $Content["Code"]; ?>"<?php echo $ReadOnly; ?> /> 
	  (Leave it empty to create random code)</td>
	  </tr>
	<tr>
	  <td align="left">Price</td>
	  <td align="left"><input type="text" name="Price" id="Price" class="Textfield" value="<?php echo $Content["Price"]; ?>" onKeyPress="return numbersonly(event, false)" />&nbsp;<?php echo DEFAULT_CURRENCY; ?></td>
	  </tr>
	<tr>
	  <td align="left">Color</td>
	  <td align="left"><?php
	  	$Web->query("select * from p_color where Status='".ACTIVE."' order by Title asc");
		if($Web->num_rows()>0)
		{
			echo '<table cellpadding="0" cellspacing="0" border="0">';
			echo '<tr><td colspan="2"><a onclick="SelectAll(\'Colors\');">All Color</a></td></tr>';
			while($Web->next_Record())
			{
				if($RecordID!='')
				{
					$Checked='';
					$Web2->query("select * from product_color where ProductID='$RecordID' and ColorID='".$Web->f('TableID')."'");
					if($Web2->num_rows()>0)
						$Checked=' checked="checked"';
				}
				echo '<tr>';
				echo '<td><input type="checkbox" name="Colors[]" id="Color_'.$Web->f('TableID').'" value="'.$Web->f('TableID').'"'.$Checked.' /></td>';
				echo '<td><label for="Color_'.$Web->f('TableID').'">'.$Web->f('Title').'</label></td>';
				echo '</tr>';
			}
			echo '</table>';
		}
		else
			echo "<strong>Please first add color</strong>";
	  ?></td>
	  </tr>
	<tr>
	  <td align="left">Size</td>
	  <td align="left"><?php
	  	$Count=0;
	  	$Web->query("select * from p_size where Status='".ACTIVE."' order by Title asc");
		if($Web->num_rows()>0)
		{
			echo '<table cellpadding="0" cellspacing="0" border="0">';
			echo '<tr><td colspan="2"><a onclick="SelectAll(\'Sizes\');">All Size</a></td></tr>';
			while($Web->next_Record())
			{
				if($RecordID!='')
				{
					$Checked='';
					$Stock='';
					$Web2->query("select * from product_size where ProductID='$RecordID' and SizeID='".$Web->f('TableID')."'");
					if($Web2->num_rows()>0)
					{
						$Web2->next_Record();
						$Checked=' checked="checked"';
						$Stock = $Web2->f('Stock');
					}
				}
				echo '<tr>';
				echo '<td><input type="checkbox" name="Sizes[]" id="Size_'.$Web->f('TableID').'" value="'.$Web->f('TableID').'"'.$Checked.' /></td>';
				echo '<td><label for="Size_'.$Web->f('TableID').'" id="Label_'.$Count.'">'.$Web->f('Title').'</label></td>';
				echo '</tr>';
				$Count++;
			}
			echo '</table>';
		}
		else
			echo "<strong>Please first add size</strong>";
	  ?></td>
	  </tr>
	<tr>
	  <td colspan="2" align="left">Description<br /><textarea name="Description" id="Description" class="tinymce"><?php echo $Content["Description"]; ?></textarea></td>
	  </tr>
	<tr>
	  <td align="left">In Featured</td>
	  <td align="left"><table cellpadding="0" cellspacing="0" border="0">
	    <tr>
	      <?php
            	foreach($ActiveInactive as $Key => $Value)
            	{
					$Checked='';
					if($Key==$Content["InFeatured"])
						$Checked = ' checked';
			?>
	      <td><input type="radio" name="InFeatured" value="<?php echo $Key; ?>" id="InFeatured_<?php echo $Key; ?>2"<?php echo $Checked; ?> /></td>
	      <td>&nbsp;</td>
	      <td><label for="InFeatured_<?php echo $Key; ?>2"><?php echo $Value; ?></label></td>
	      <td>&nbsp;&nbsp;</td>
	      <?php	
            	}
            ?>
	      </tr>
	    </table></td>
	  </tr>
	<tr>
	  <td align="left">Add in Best Sell</td>
	  <td align="left"><table cellpadding="0" cellspacing="0" border="0">
	    <tr>
	      <?php
            	foreach($ActiveInactive as $Key => $Value)
            	{
					$Checked='';
					if($Key==$Content["InHome"])
						$Checked = ' checked';
			?>
	      <td><input type="radio" name="InHome" value="<?php echo $Key; ?>" id="InHome_<?php echo $Key; ?>2"<?php echo $Checked; ?> /></td>
	      <td>&nbsp;</td>
	      <td><label for="InHome_<?php echo $Key; ?>2"><?php echo $Value; ?></label></td>
	      <td>&nbsp;&nbsp;</td>
	      <?php	
            	}
            ?>
	      </tr>
	    </table></td>
	  </tr>
	<tr>
	  <td align="left">Active</td>
	  <td align="left">
	    <table cellpadding="0" cellspacing="0" border="0">
	      <tr>
	        <?php
            	foreach($ActiveInactive as $Key => $Value)
            	{
					$Checked='';
					if($Key==$Content["Status"])
						$Checked = ' checked';
			?>
	        <td><input type="radio" name="Status" value="<?php echo $Key; ?>" id="Status_<?php echo $Key; ?>"<?php echo $Checked; ?> /></td>
	        <td>&nbsp;</td>
	        <td><label for="Status_<?php echo $Key; ?>"><?php echo $Value; ?></label></td>
	        <td>&nbsp;&nbsp;</td>
	        <?php	
            	}
            ?>
	        </tr>
	      </table>
	    </td>
	  </tr>
    <tr>
    	<td>&nbsp;</td>
        <td align="left"><input type="submit" name="SubmitBtn" id="SubmitBtn" value="Submit" class="Button" /></td>
    </tr>
</table>
</form>
</div>
<?php if($Content["TableID"]!='') { ?>
<script>
LoadSubCategory('<?php echo $Content["ParentID"]; ?>', '<?php echo $Content["CategoryID"]; ?>');
</script>
<?php } ?>