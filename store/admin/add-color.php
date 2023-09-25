<?php
$RecordID='';
$Content["Status"]=ACTIVE;
$Content["ColorCode"]='0000ff';
$Title='Add New Color';
if(isset($_REQUEST['PageType']) && isset($_REQUEST['ID']) && $_REQUEST['PageType']=='Edit' && $_REQUEST['ID']!='')
{
	$RecordID = $_REQUEST['ID'];
	$Content = $Web->getRecord($_REQUEST['ID'], "TableID", "p_color");
	if($Content["TableID"]=='')
		$Web->Redirect("index.php?page=product-colors");
	$Title='Edit Color';
}
?>
<div class="Page-Data">
<h1><?php echo $Title; ?></h1>
<form name="RecordForm" id="RecordForm" method="post" action="" onsubmit="return ValidateColor();" enctype="multipart/form-data">
<input type="hidden" name="ValidateColorFlag" id="ValidateColorFlag" />
<input type="hidden" name="RecordID" id="RecordID" value="<?php echo $RecordID; ?>" />
<table width="100%" cellpadding="5" cellspacing="0" border="0">
	<tr>
    	<td width="25%" align="left">Title</td>
        <td width="75%" align="left"><input type="text" name="Title" id="Title" class="Textfield" value="<?php echo $Content["Title"]; ?>" /></td>
    </tr>
	<tr>
	  <td align="left">Color Code</td>
	  <td align="left">
      <div id="colorSelector"><div style="background-color:#<?php echo $Content["ColorCode"]; ?>;"></div></div>
      <input type="hidden" name="ColorCode" id="ColorCode" value="<?php echo $Content["ColorCode"]; ?>" />
      </td>
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
<script>
$(document).ready(function(){
	$('#colorSelector').ColorPicker({
		color: '#0000ff',
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#colorSelector div').css('background-color', '#'+hex);
			$('#ColorCode').val(hex);
		}
	});
});
</script>