<?php
include_once("../classes/config.php");
extract($_REQUEST);
$ErrorMsg='';

if(!empty($_FILES['file']))
{
	$File = $_FILES['file'];
	$Extension = $Web->GetExtension($File["name"]);
	$FileSize = $File["size"];
	
	$Type		= $_SESSION['UploadFileType'];
	$RecordID	= $_SESSION['RecordID'];
	$Table		= $_SESSION['Table'];
	$Field		= $_SESSION['Field'];
	$Location	= "../".$_SESSION['Location'];

	if($Type=='Song')
	{
		$Extensions = $SongExtArray;
		$MFSize = SMB;
	}
		
	$Exts = implode(", ", $SongExtArray);
	
	if(!in_array($Extension, $Extensions))
	{
		$Exts = implode(", ", $ExtArray);
		$ErrorMsg = 'Only '.$Exts.' files allowed.<br />Please try again.';
	}
	else if($FileSize>SONG_SIZE)
	{
		$ErrorMsg = 'Maximum file size should be '.$MFSize.'MB';
	}
	else
	{
		$VideoFileName = $Web->uploadfile($File, $Location, $Extension);
		if($VideoFileName!='')
		{
			$Web->query("update $Table set 
			$Field='$VideoFileName' 
			where TableID='$RecordID'");
			
			$ErrorMsg = 'File uploaded successfully!';
		}
		else
		{
			$ErrorMsg = 'File not uploaded successfully';
		}
	}
	echo $ErrorMsg;
}
?>