<div class="Page-Data">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
    	<td align="left" width="80%"><h1>Product Size</h1></td>
        <td align="right" width="20%"><a href="index.php?page=add-size">Add New Size</a></td>
    </tr>
</table>
<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="60c2e9" style="border-collapse:collapse;">
	<tr class="Data-Heading">
	  <td width="85%" align="left">Size</td>
        <td width="5%" align="center">Active</td>
        <td width="5%" align="center">Edit</td>
        <td width="5%" align="center">Delete</td>
    </tr>
    <?php
		$Web->query("select * from p_size where TableID!='-1' order by Title asc");
		if($Web->num_rows()>0)
		{
			while($Web->next_Record())
			{
	?>
    <tr id="Data_<?php echo $Web->f('TableID'); ?>">
      <td align="left"><?php echo $Web->f('Title'); ?></td>
        <td align="center"><?php echo $ActiveInactive[$Web->f('Status')]; ?></td>
        <td align="center"><a href="index.php?page=add-size&PageType=Edit&ID=<?php echo $Web->f('TableID'); ?>"><img src="images/edit.png" alt="" /></a></td>
        <td align="center"><a onclick="DeleteData('<?php echo $Web->f('TableID'); ?>', 'ValidateSizeFlag');"><img src="images/delete.png" alt="" /></a></td>
    </tr>
    <?php			
			}
		}
		else
		{
			echo '<tr><td colspan="4" align="center"><div class="NoRecord" align="center">No record found</div></td></tr>';
		}
	?>
</table>
</div>