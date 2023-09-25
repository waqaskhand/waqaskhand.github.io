<?php
if(isset($_REQUEST['PageType']) && isset($_REQUEST['ID']) && $_REQUEST['PageType']=='Edit' && $_REQUEST['PageType']!='')
{
	$Content = $Web->getRecord($_REQUEST['ID'], "TableID", "content");
?>
<script>
$(document).ready(function(){
	$("#SmallContent").limita({
		limit: 123,
		id_result: "counter",
		alertClass: "alert"
	});
});
</script>
<div class="Page-Data">
<h1>Edit Page Content</h1>
<form name="ContentForm" id="ContentForm" method="post" action="" onsubmit="return ValidateContent();" enctype="multipart/form-data">
<input type="hidden" name="ValidateContentFlag" id="ValidateContentFlag" />
<input type="hidden" name="RecordID" id="RecordID" value="<?php echo $_REQUEST['ID']; ?>" />
<input type="hidden" name="SmallContent" value="NULL" />
<table width="100%" cellpadding="5" cellspacing="0" border="0">
	<tr>
    	<td width="25%" align="left">Title</td>
        <td width="75%" align="left"><input type="text" name="Title" id="Title" class="Textfield" value="<?php echo $Content['Title']; ?>" /></td>
    </tr>
    <tr>
      <td colspan="2" align="left">Content</td>
      </tr>
    <tr>
    	<td colspan="2" align="left"><textarea name="Content" id="Content" class="tinymce"><?php echo $Content["Content"]; ?></textarea></td>
      </tr>
    <tr>
    	<td>&nbsp;</td>
        <td align="left"><input type="submit" name="SubmitBtn" id="SubmitBtn" value="Submit" class="Button" /></td>
    </tr>
</table>
</form>
</div>
<?php	
}
else
{
?>
<div class="Page-Data">
<h1>Website Content</h1>
<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="60c2e9" style="border-collapse:collapse;">
	<tr class="Data-Heading">
    	<td width="95%" align="left">Title</td>
        <td width="5%" align="center">Edit</td>
    </tr>
    <?php
		$Web->query("select * from content order by TableID asc");
		if($Web->num_rows()>0)
		{
			while($Web->next_Record())
			{
	?>
    <tr>
    	<td align="left"><?php echo $Web->f('Title'); ?></td>
        <td align="center"><a href="index.php?page=content&PageType=Edit&ID=<?php echo $Web->f('TableID'); ?>"><img src="images/edit.png" alt="" /></a></td>
    </tr>
    <?php			
			}
		}
		else
		{
			echo '<tr><td colspan="2" align="center"><div class="NoRecord" align="center">No record found</div></td></tr>';
		}
	?>
</table>
</div>
<?php
}
?>