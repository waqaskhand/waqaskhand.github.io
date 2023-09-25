<?php
$Web->query("select * from subscriptions order by Date desc");
$Count = $Web->num_rows();
?>
<div class="Page-Data">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
    	<td align="left" width="80%"><h1>Subscribers</h1></td>
        <td align="right" width="20%"><?php if($Count>0) { ?><a href="export-subscribers.php" target="_blank">Export in Excel</a><?php } ?></td>
    </tr>
</table>
<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="60c2e9" style="border-collapse:collapse;">
    <tr class="Data-Heading">
        <td width="5%" align="center">S.No</td>
        <td width="75%" align="left">Email</td>
        <td width="15%" align="left">Date</td>
        <td width="5%" align="center">Delete</td>
    </tr>
    <?php
		if($Count>0)
		{
			$Sno=0;
			while($Web->next_Record())
			{
				$Sno++;
	?>
    <tr id="Data_<?php echo $Web->f('TableID'); ?>">
    	<td align="center"><?php echo $Sno; ?></td>
    	<td align="left"><?php echo $Web->f('Email'); ?></td>
        <td align="left"><?php echo $Web->FormatDate($Web->f('Date')); ?></td>
    	<td align="center"><a onclick="DeleteData('<?php echo $Web->f('TableID'); ?>', 'ValidateSubscriberFlag');"><img src="images/delete.png" alt="" /></a></td>
    </tr>
    <?php			
			}
		}
		else
		{
			echo '<tr><td colspan="4" align="center"><div class="NoRecord" align="center">No subscribers found</div></td></tr>';
		}
	?>
</table>
</div>