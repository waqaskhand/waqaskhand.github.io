<?php
class SystemAdmin extends WebFunctions
{
	function Login($Username='', $Password='')
	{
		$Return["Error"]=0;
		$Return["Msg"]="";
		if($Username!='' && $Password!='')
		{
			$this->query("select * from admin where Username='$Username' and Password='$Password'");
			if($this->num_rows()>0)
			{
				$this->next_Record();
				$_SESSION[ADMIN_LOGGED_IN]["TableID"] = $this->f('TableID');
				$_SESSION[ADMIN_LOGGED_IN]["Name"] = $this->f('Name');
				$_SESSION[ADMIN_LOGGED_IN]["LastLogin"] = $this->f('LastLogin');
				
				$this->query("update admin set LastLogin='".$this->GetCurrentDateTime()."' where TableID='".$this->f('TableID')."'");
			}
			else
			{
				$Return["Error"]=1;
				$Return["Msg"]="Wrong Username or password. Please try again.";
			}
		}
		return $Return;
	}
	
	function ChangePassword($Data)
	{
		extract($Data);
		$this->query("select * from admin where 
					 TableID='".$_SESSION[ADMIN_LOGGED_IN]["TableID"]."' and 
					 Password='".md5($OldPassword)."'");
		if($this->num_rows()>0)
		{
			$this->query("update admin set 
						 Password='".md5($NewPassword)."' 
						 where TableID='".$_SESSION[ADMIN_LOGGED_IN]["TableID"]."'");
			$_SESSION[CONFIRMATION_MESSAGE] = 'Password Updated Successfully';
		}
		else
			$_SESSION[CONFIRMATION_MESSAGE] = 'You entered wrong old password';
	}
}
?>