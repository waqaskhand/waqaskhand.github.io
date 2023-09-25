<?php
$RecordID='';
$Content["Status"]=ACTIVE;
$Title='Add New Category';
if(isset($_REQUEST['PageType']) && isset($_REQUEST['ID']) && $_REQUEST['PageType']=='Edit' && $_REQUEST['ID']!='')
{
	$RecordID = $_REQUEST['ID'];
	$Content = $Web->getRecord($_REQUEST['ID'], "TableID", "p_category");
	if($Content["TableID"]=='')
		$Web->Redirect("index.php?page=product-categories");
		
	$Title='Edit Category';
}
?>
<div class="Page-Data">
<h1><?php echo $Title; ?></h1>
<form name="RecordForm" id="RecordForm" method="post" action="" onsubmit="return ValidateCategory();" enctype="multipart/form-data">
<input type="hidden" name="ValidateCategoryFlag" id="ValidateCategoryFlag" />
<input type="hidden" name="RecordID" id="RecordID" value="<?php echo $RecordID; ?>" />
<input type="hidden" name="returnPage" id="returnPage" value="product-categories" />
<table width="100%" cellpadding="5" cellspacing="0" border="0">
	<tr>
	  <td align="left">Category</td>
	  <td align="left">
      <select name="ParentID" id="ParentID" class="DropDown">
      	<option value=""<?php if($Content["ParentID"]=='') { echo " selected"; } ?>>Please Select</option>
        <option value="0"<?php if($Content["ParentID"]=='0') { echo " selected"; } ?>>Parent Category</option>
        <?php echo $Web->getSelectDropDownWhereField($Content["ParentID"], "TableID", "Title", "ParentID", "0", "p_category", "Sequence"); ?>
      </select>
      </td>
	  </tr>
	<tr>
    	<td width="25%" align="left">Title</td>
        <td width="75%" align="left"><input type="text" name="Title" id="Title" class="Textfield" value="<?php echo $Content["Title"]; ?>" /></td>
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