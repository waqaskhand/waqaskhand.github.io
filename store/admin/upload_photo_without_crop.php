<?php
require_once("../classes/config.php");

extract($_REQUEST);

if(isset($_REQUEST['ValidateUploadImageFlag']) && $_REQUEST['ValidateUploadImageFlag']=='true')
{
	$Extension = $Web->GetExtension($_FILES['ImageFile']['name']);
	if($Extension=='jpg' || $Extension=='png' || $Extension=='gif')
	{
		$FileName = $Web->uploadfile($_FILES['ImageFile'], "../".IMAGES_FOLDER, $Extension);
		$ImagePath = "../".IMAGES_FOLDER."/".$FileName;
		$UploadedImageWidth		= $Web->getWidth($ImagePath);
		$UploadedImageHeight	= $Web->getHeight($ImagePath);
		
		/*if($UploadedImageWidth!=$_SESSION['ThumbWidth'] 
		|| $UploadedImageHeight!=$_SESSION['ThumbHeight'])
		{
			unlink($ImagePath);
			$ErrorMsg = 'Image width must be '.$_SESSION['ThumbWidth'].'px and height must be '.$_SESSION['ThumbHeight'].'px';
		}
		else
		{*/
			if($_SESSION['ImageType']==IMAGE_TYPE_NORMAL)
			{
				$Web->query("update ".$_SESSION['TableName']." set ".$_SESSION['FieldName']."='".$FileName."' where TableID='".$_SESSION['RecordID']."'");
			}
			else
			{
				$Web->query("insert into web_images set 
				FileName='".$FileName."', 
				Type='".$_SESSION['ImageType']."', 
				ParentID='".$_SESSION['RecordID']."'");
			}
?>
<script>
window.opener.location.reload();
window.close();
</script>
<?php					
		//}
	}
	else
	{
		$ErrorMsg = 'Only jpg, gif and png images allowed';
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upload User Photo</title>
<script language="javascript" type="text/javascript" src="js/j-old.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.crop.js"></script>
<script>
function CheckExtension(filename)
{
	var parts = filename.split('.');
	var extension = parts[parts.length-1];
	return extension.toLowerCase();
}

function ValidateImage()
{
	with(document.UploadImageForm)
	{
		if(ImageFile.value=='')
		{
			alert("Please select image to upload.");
			ImageFile.focus();
			return false;s
		}
		else if(CheckExtension(ImageFile.value)!='jpg' && 
		CheckExtension(ImageFile.value)!='png' && 
		CheckExtension(ImageFile.value)!='gif')
		{
			alert("Only Jpg, Gif and Png Images Allowed");
			ImageFile.focus();
			return false;
		}
		ValidateUploadImageFlag.value='true';
	}
	return true;
}

</script>
<style>
body {
	margin:0px;
	padding:0px;
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	background-image: url(backoffice/images/bg.jpg);
	background-repeat: repeat;
	background-position: top;
}
h1{
	font-size:30px;
	font-weight:bold;
	color:#333;
	padding:0px;
	margin:0px;
}
strong {
	font-size:15px;
}
#save_thumb {
	text-align:right;
	font-size:20px;
}
.ErrorMsg {
	color:#f00;
}
</style>
</head>
<body>
<table width="100%" cellpadding="5" cellspacing="0" border="0">
	<tr>
    	<td align="left"><h1>Image Manager</h1></td>
    </tr>
    <?php
		if(isset($ErrorMsg))
		{
			echo '<tr><td align="left"><div align="left" class="ErrorMsg">'.$ErrorMsg.'</div><hr /></td></tr>';
		}
	?>
    <tr>
    	<td>
        	<form name="UploadImageForm" id="UploadImageForm" method="post" action="" onSubmit="return ValidateImage();" enctype="multipart/form-data">
            <input type="hidden" name="ValidateUploadImageFlag" id="ValidateUploadImageFlag" />
            <input type="hidden" name="Width" id="Width" value="<?php echo $_REQUEST["Width"]; ?>" />
            <input type="hidden" name="Height" id="Height" value="<?php echo $_REQUEST["Height"]; ?>" />
            <table cellpadding="5" cellspacing="0" border="0" align="left">
            	<tr>
                	<td><input type="file" name="ImageFile" id="ImageFile" />&nbsp;<input type="submit" name="Submit" id="Submit" value="Upload Image" /></td>
                </tr>
                <tr>
                	<td align="left">Image width <?php echo $_SESSION['ThumbWidth']; ?>px</td>
                </tr>
                <tr>
                	<td align="left">Image height <?php echo $_SESSION['ThumbHeight']; ?>px</td>
                </tr>
            </table>
            </form>
        </td>
    </tr>
</table>
</body>
</html>