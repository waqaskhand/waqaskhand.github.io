<?php
$Web->query("select * from web_users order by JoinDate desc");
$Count = $Web->num_rows();
?>
<div class="Page-Data">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
    	<td align="left" width="80%"><h1>Website Users</h1></td>
        <td align="right" width="20%"></td>
    </tr>
</table>
<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="60c2e9" style="border-collapse:collapse;">
    <tr class="Data-Heading">
        <td width="5%" align="center">S.No</td>
        <td width="30%" align="left">Name</td>
        <td width="25%" align="left">Email</td>
        <td width="15%" align="left">Mobile Number</td>
        <td width="15%" align="left">Join Date</td>
        <td width="5%" align="left">Status</td>
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
    	<td align="left"><?php echo $Web->f('Name'); ?></td>
        <td align="left"><?php echo $Web->f('Email'); ?></td>
        <td align="left"><?php echo $Web->f('Mobile'); ?></td>
        <td align="left"><?php echo $Web->FormatDate($Web->f('JoinDate')); ?></td>
        <td align="left"><?php echo $ActiveInactive[$Web->f('Status')]; ?></td>
    	<td align="center"><a onclick="DeleteData('<?php echo $Web->f('TableID'); ?>', 'ValidateWebUserFlag');"><img src="images/delete.png" alt="" /></a></td>
    </tr>
    <?php			
			}
		}
		else
		{
			echo '<tr><td colspan="7" align="center"><div class="NoRecord" align="center">No record found</div></td></tr>';
		}
	?>
</table>
</div>