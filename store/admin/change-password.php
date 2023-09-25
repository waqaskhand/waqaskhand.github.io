
<div class="Page-Data">
<h1>Change Password</h1>
<form name="RecordForm" id="RecordForm" method="post" action="" onsubmit="return ValidateChangePassword();">
<input type="hidden" name="ChangePasswordFlag" id="ChangePasswordFlag" />
<table width="100%" cellpadding="5" cellspacing="0" border="0">
	<tr>
        <td width="25%" align="left">Old Password</td>
        <td width="75%" align="left"><input type="password" name="OldPassword" id="OldPassword" class="Textfield" /></td>
    </tr>
    <tr>
        <td align="left">New Password</td>
        <td align="left"><input type="password" name="NewPassword" id="NewPassword" class="Textfield" /></td>
    </tr>
    <tr>
        <td align="left">Confirm Password</td>
        <td align="left"><input type="password" name="ConfirmPassword" id="ConfirmPassword" class="Textfield" /></td>
    </tr>
    <tr>
    	<td>&nbsp;</td>
        <td align="left"><input type="submit" name="SubmitBtn" id="SubmitBtn" value="Submit" class="Button" /></td>
    </tr>
</table>
</form>
</div>