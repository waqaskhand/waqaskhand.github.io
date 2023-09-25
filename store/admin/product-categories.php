<div class="Page-Data">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
    	<td align="left" width="80%"><h1>Product Categories</h1></td>
        <td align="right" width="20%"><a href="index.php?page=add-category">Add New Category</a></td>
    </tr>
</table>
<form name="UpdateSequenceForm" id="UpdateSequenceForm" method="post" action="">
<input type="hidden" name="UpdateSequenceFlag" id="UpdateSequenceFlag" value="true" />
<input type="hidden" name="TableName" id="TableName" value="p_category" />
<input type="hidden" name="returnPage" id="returnPage" value="product-categories" />
<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="60c2e9" style="border-collapse:collapse;">
    <tr class="Data-Heading">
        <td width="5%" align="center">S.No</td>
        <td width="70%" align="left">Category</td>
        <td width="10%" align="center">Sequence</td>
        <td width="5%" align="center">Active</td>
        <td width="5%" align="center">Edit</td>
        <td width="5%" align="center">Delete</td>
    </tr>
    <?php
		$Web->query("select * from p_category where 
		ParentID=0 or 
		ParentID in (select TableID from p_category where ParentID=0)
		order by ParentID asc, Sequence asc");
		if($Web->num_rows()>0)
		{
			$Sno=0;
			while($Web->next_Record())
			{
				if($Web->f('ParentID')!=$LastParentID)
				{
					$Sno=0;
					$LastParentID = $Web->f('ParentID');
					if($Web->f('ParentID')=='0')
						$Parent_Cat='Parent';
					else
						$Parent_Cat = $Web2->getFieldData("Title", "TableID", $Web->f('ParentID'), "p_category");
	?>
    <tr>
    	<td colspan="6" align="left"><strong><?php echo $Parent_Cat; ?></strong></td>
    </tr>
    <?php				
				}
				$Sno++;
	?>
    <tr id="Data_<?php echo $Web->f('TableID'); ?>" class="Data-Tr">
      <td align="center"><?php echo $Sno; ?></td>
      <td align="left"><?php echo $Web->f('Title'); ?></td>
        <td align="center"><input type="hidden" name="RecordIDs[]" value="<?php echo $Web->f('TableID'); ?>" /><input type="text" name="Sequence[]" value="<?php echo $Web->f('Sequence'); ?>" style="width:50px;" /></td>
        <td align="center"><?php echo $ActiveInactive[$Web->f('Status')]; ?></td>
        <td align="center"><a href="index.php?page=add-category&PageType=Edit&ID=<?php echo $Web->f('TableID'); ?>"><img src="images/edit.png" alt="" /></a></td>
        <td align="center"><a onclick="DeleteData('<?php echo $Web->f('TableID'); ?>', 'ValidateCategoryFlag');"><img src="images/delete.png" alt="" /></a></td>
    </tr>
    <?php			
			}
	?>
    <tr>
    	<td colspan="6" align="right"><input type="submit" name="SubmitBtn" id="SubmitBtn" value="Update Sequence" class="Button" /></td>
    </tr>
    <?php		
		}
		else
		{
			echo '<tr><td colspan="6" align="center"><div class="NoRecord" align="center">No record found</div></td></tr>';
		}
	?>
</table>
</form>
</div>