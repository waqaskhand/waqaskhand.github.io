<?php
$RecordID='';
$Title='Add New Banner';
$Content["Status"]=ACTIVE;
$Content["TypeID"]=ACTIVE;
if(isset($_REQUEST['PageType']) && isset($_REQUEST['ID']) && $_REQUEST['PageType']=='Edit' && $_REQUEST['ID']!='')
{
	$RecordID = $_REQUEST['ID'];
	$Content = $Web->getRecord($_REQUEST['ID'], "TableID", "banners");
	if($Content["TableID"]=='')
		$Web->Redirect("index.php?page=banners");
		
	$Title='Edit Banner';
}
?>
<div class="Page-Data">
<h1><?php echo $Title; ?></h1>
<form name="RecordForm" id="RecordForm" method="post" action="" onsubmit="return ValidateBanner();" enctype="multipart/form-data">
<input type="hidden" name="ValidateBannerFlag" id="ValidateBannerFlag" />
<input type="hidden" name="RecordID" id="RecordID" value="<?php echo $RecordID; ?>" />
<table width="100%" cellpadding="5" cellspacing="0" border="0">
	<tr>
    	<td width="25%" align="left">Type</td>
        <td width="75%" align="left"><table cellpadding="0" cellspacing="0" border="0">
	      <tr>
	        <?php
            	foreach($BannerTypes as $Key => $Value)
            	{
					$Checked='';
					if($Key==$Content["TypeID"])
						$Checked = ' checked';
			?>
	        <td><input onchange="ReloadBannerFields(this.value);" type="radio" name="TypeID" value="<?php echo $Key; ?>" id="TypeID_<?php echo $Key; ?>"<?php echo $Checked; ?> /></td>
	        <td>&nbsp;</td>
	        <td><label for="TypeID_<?php echo $Key; ?>"><?php echo $Value; ?></label></td>
	        <td>&nbsp;&nbsp;</td>
	        <?php	
            	}
            ?>
	        </tr>
	      </table></td>
    </tr>
    <tr>
    	<td align="left">Link</td>
        <td align="left"><input type="text" name="Link" id="Link" class="Textfield" value="<?php echo $Content["Link"]; ?>" /></td>
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
ReloadBannerFields('<?php echo $Content["TypeID"]; ?>');
</script>
<?php } ?>