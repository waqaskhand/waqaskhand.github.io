<div class="Login-Box">
	<form name="LoginForm" id="LoginForm" method="post" onsubmit="return ValidateLoginForm('<?php echo ADMIN_LOGIN_TRIGGER; ?>');">
    <input type="hidden" name="Trigger" />
    <table width="100%" cellpadding="5" cellspacing="0" border="0">
    	<tr>
        	<td align="center"><h1>Website Control Panel</h1></td>
        </tr>
        <tr>
        	<td align="left">Username:<div align="center"><input type="text" name="Username" id="Username" /></div></td>
        </tr>
        <tr>
        	<td align="left">Password:<div align="center"><input type="password" name="Password" id="Password" /></div></td>
        </tr>
        <tr>
        	<td align="right"><input type="submit" name="SubmitBtn" id="SubmitBtn" value="Login" /></td>
        </tr>
    </table>
    </form>
</div>