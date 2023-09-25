<?php
class content extends WebFunctions
{
	function ManageContent($Data)
	{
		extract($Data);
		
		if($PageType=='Delete')
		{
			$File = $this->getFieldData("FileName", "TableID", $RecordID, "web_images");
			if($File!='')
			{
				unlink("../".IMAGES_FOLDER."/".$File);
				unlink("../".ORIGINAL_IMAGES."/".$File);
			}
			$this->query("delete from web_images where TableID='$RecordID'");
		}
		else
		{
			$this->query("update content set 
			Title='$Title', 
			SmallContent='$SmallContent', 
			Content='$Content' 
			where TableID='$RecordID'");
			$_SESSION[CONFIRMATION_MESSAGE] = "Content Updated Successfully";
		}
	}
	
	function DeleteRecordImage($Data)
	{
		extract($Data);
		$ImageName = $this->getFieldData($Field, "TableID", $RecordID, $Table);
		unlink('../'.IMAGES_FOLDER."/".$ImageName);
		unlink('../'.ORIGINAL_IMAGES."/".$ImageName);
		$this->query("update ".$Table." set ".$Field."='' where TableID='$RecordID'");
	}
	
	function UpdateSequence($Data)
	{
		extract($Data);
		$Count=0;
		foreach($RecordIDs as $RecordID)
		{
			$this->query("update $TableName set Sequence='".$Sequence[$Count]."' where TableID='$RecordID'");
			$Count++;
		}
		//$_SESSION[CONFIRMATION_MESSAGE] = "Sequence Updated Successfully";
	}
	
	function DeleteWebUser($Data)
	{
		extract($Data);
		$this->query("delete from web_users where TableID='$RecordID'");
	}
	
	function ManageSettings($Data)
	{
		unset($_SESSION[WEBSITE_SETTINGS]);
		extract($Data);
		$Count=0;
		foreach($TableIDs as $TableID)
		{
			$Value = $Settings[$Count];
			$this->query("update settings set Data='$Value' where TableID='$TableID'");
			$Count++;
		}
		$_SESSION[CONFIRMATION_MESSAGE] = "Settings Updated Successfully";
	}
	
	function ManageBanner($Data)
	{
		extract($Data);
		$Count=0;
		foreach($RecordIDs as $RecordID)
		{
			$CategoryID = $CategoryIDs[$Count];
			$this->query("select TableID from p_category where TableID='$CategoryID' and ParentID='0'");
			if($this->num_rows()>0)
			{
				$Query = "update web_images set TitleOne='$CategoryID', TitleTwo='category' where TableID='$RecordID'";
			}
			else
			{
				$Query = "update web_images set TitleOne='$CategoryID', TitleTwo='sub-category' where TableID='$RecordID'";
			}
			$this->query($Query);
			$Count++;
		}
	}
	function DeleteSubscriber($Data)
	{
		extract($Data);
		$this->query("delete from subscriptions where TableID='$RecordID'");
	}
}
?>