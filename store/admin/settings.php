<div class="Page-Data">
	<h1>Website Settings</h1>
	<form name="RecordForm" id="RecordForm" method="post" action="">
		<input type="hidden" name="ValidateSettingsFlag" id="ValidateSettingsFlag" value="true" />
		<table width="100%" cellpadding="5" cellspacing="0" border="0">
		<?php
			$Web->query("select * from settings order by TableID asc");
			if($Web->num_rows()>0)
			{
				while($Web->next_Record())
				{
		?>
            <tr>
            	<td align="left"><?php echo $Web->f('Label'); ?></td>
            	<td align="left">
                	<?php if($Web->f('Type')==1) { ?>
                    <input type="hidden" name="TableIDs[]" value="<?php echo $Web->f('TableID'); ?>" />
                    <input type="text" name="Settings[]" class="Textfield" value="<?php echo $Web->f('Data'); ?>" />
					<?php } 
					else if($Web->f('Type')==2) { ?>
					<table cellpadding="0" cellspacing="0" border="0">
	      <tr>
	        <?php
            	foreach($ActiveInactive as $Key => $Value)
            	{
					$Checked='';
					if($Key==$Web->f('Data'))
						$Checked = ' checked';
						
					$Name = 'radio_'.$Web->f('TableID');
			?>
	        <td><input type="radio" name="<?php echo $Name; ?>" value="<?php echo $Key; ?>" id="<?php echo $Name."_".$Key; ?>"<?php echo $Checked; ?> /></td>
	        <td>&nbsp;</td>
	        <td><label for="<?php echo $Name."_".$Key; ?>"><?php echo $Value; ?></label></td>
	        <td>&nbsp;&nbsp;</td>
	        <?php	
            	}
            ?>
	        </tr>
	      </table>
					<?php } ?>
                </td>
            </tr>
		<?php
				}
		?>
			<tr>
				<td width="25%">&nbsp;</td>
				<td width="75%" align="left"><input type="submit" name="SubmitBtn" id="SubmitBtn" value="Submit" class="Button" /></td>
			</tr>
		<?php
			}
			else
			{
				echo '<tr><td align="center">No settings found</td></tr>';
			}
		?>
		</table>
	</form>
</div>