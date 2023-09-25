<?php
include_once("../classes/config.php");
if($Type=='Song')
{
	$Exts = implode(", ", $SongExtArray);
	$PageTitle='Song';
	$Table = 'music';
	$Field = 'Song';
	$Location = SONG_FOLDER;
	$MFSize = SMB;
}
$_SESSION['UploadFileType']	= $Type;
$_SESSION['RecordID']		= $RecordID;
$_SESSION['Table']			= $Table;
$_SESSION['Field']			= $Field;
$_SESSION['Location']		= $Location;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upload <?php echo $PageTitle; ?></title>
<link rel="stylesheet" type="text/css" href="css/style.css?v=<?php echo VERSION; ?>112" />
<script src="../js/jquery.js"></script>
<script src="upload.js"></script>
</head>
<body>
<div style="padding:20px;">
<form name="uploadVideo" id="uploadVideo" method="post" action="" enctype="multipart/form-data" onsubmit="return UploadVideo();">
    <div align="left" style="margin-bottom:20px;"><h1>Upload <?php echo $PageTitle; ?></h1></div>
    <input type="file" name="file" id="file" />
    <input type="submit" name="SubmitBtn" id="SubmitBtn" value="Submit" />&nbsp;&nbsp;
    <div class="ProgressBar" id="ProgressBar">
        <div class="PercentAge" id="PercentAge"></div>
        <div class="ProgressBg" id="ProgressBg" style="width:0%;">&nbsp;</div>
    </div>
    <!--<div id="uploadProgress"></div>-->
    <p>* Only <strong><?php echo $Exts; ?></strong> files allowed.</p>
    <p>* Maximum video size should be <strong><?php echo $MFSize; ?>MB</strong></p>
    <div id="ErrorMsg"></div>
</form>
</div>
</body>
</html>