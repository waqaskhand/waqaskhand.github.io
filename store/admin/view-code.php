<div class="Page-Data">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
    	<td align="left" width="80%"><h1>Discount Codes</h1></td>
        <td align="right" width="20%"><a href="index.php?page=generate-code">Generate New Codes</a></td>
    </tr>
</table>
<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="60c2e9" style="border-collapse:collapse;">
	<tr class="Data-Heading">
	  <td width="5%" align="center">S.No</td>
	  <td width="15%" align="left">Discount</td>
	  <td width="20%" align="left">Code</td>
	  <td width="20%" align="left">Creation Date</td>
        <td width="10%" align="center">Used</td>
        <td width="15%" align="left">Used Date</td>
        <td width="15%" align="left">User</td>
    </tr>
    <?php
		$Web->query("select * from discounts order by Discount asc");
		if($Web->num_rows()>0)
		{
			$Sno=0;
			while($Web->next_Record())
			{
				$Sno++;
				if($Web->f('Used')==1)
				{
					$UseDate = $Web->FormatDate($Web->f('UsedDate'), "F d Y");
					$UserName = $Web2->getFieldData("Name", 'TableID', $Web->f('UserID'), 'web_users');
				}
				else
				{
					$UseDate = '-';
					$UserName = '-';
				}
	?>
    <tr id="Data_<?php echo $Web->f('TableID'); ?>">
      <td align="left"><?php echo $Sno; ?></td>
      <td align="left"><?php echo $Web->f('Discount'); ?>%</td>
      <td align="left"><?php echo $Web->f('Code'); ?></td>
      <td align="left"><?php echo $Web->FormatDate($Web->f('CDate'), "F d Y"); ?></td>
        <td align="center"><?php echo $ActiveInactive[$Web->f('Used')]; ?></td>
        <td align="left"><?php echo $UseDate; ?></td>
        <td align="left"><?php echo $UserName; ?></td>
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