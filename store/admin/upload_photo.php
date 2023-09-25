<?php
require_once("../classes/config.php");

if (isset($_POST["upload_thumbnail"]))
{
	//Get the new coordinates to crop the image.
	$x1 = $_POST["x1"];
	$y1 = $_POST["y1"];
	$x2 = $_POST["x2"];
	$y2 = $_POST["y2"];
	$w = $_POST["w"];
	$h = $_POST["h"];
	
	//Scale the image to the thumb_width set above
	$scale = $_SESSION['ThumbWidth']/$w;
	$cropped = $Web->resizeThumbnailImage($_SESSION['ThumbImagePath'], $_SESSION['OriginalImagePath'], $w, $h, $x1, $y1, $scale);
	if($_SESSION['ImageType']==IMAGE_TYPE_NORMAL)
	{
		$Web->query("update ".$_SESSION['TableName']." set ".$_SESSION['FieldName']."='".$_SESSION['NewFileName']."' where TableID='".$_SESSION['RecordID']."'");
	}
	else
	{
		$Web->query("select max(Sequence) as Sequence from web_images where 
		Type='".$_SESSION['ImageType']."' and 
		ParentID='".$_SESSION['RecordID']."'");
		$Web->next_Record();
		if(!is_numeric($Web->f('Sequence')))
			$Sequence = 1;
		else
			$Sequence = 1 + $Web->f('Sequence');
				
		$Web->query("insert into web_images set 
		FileName='".$_SESSION['NewFileName']."', 
		Type='".$_SESSION['ImageType']."', 
		ParentID='".$_SESSION['RecordID']."', 
		Sequence='$Sequence'");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upload User Photo</title>
<script>
window.opener.location.reload();
window.close();
</script>
</head>
<body>
</body>
</html>
<?php	
	
}
else
{

extract($_REQUEST);
//$_SESSION['ThumbWidth']=$_REQUEST['Width'];
//$_SESSION['ThumbHeight']=$_REQUEST['Height'];
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
$(document).ready(function () { 
	$('#save_thumb').click(function() {
		var x1 = $('#x1').val();
		var y1 = $('#y1').val();
		var x2 = $('#x2').val();
		var y2 = $('#y2').val();
		var w = $('#w').val();
		var h = $('#h').val();
		if(x1=="" || y1=="" || x2=="" || y2=="" || w=="" || h==""){
			alert("You must make a selection first");
			return false;
		}else{
			return true;
		}
	});
});
$(window).load(function () { 
	$('#thumbnail').imgAreaSelect({ aspectRatio: '1:<?php echo $_SESSION['ThumbHeight']/$_SESSION['ThumbWidth'];?>', onSelectChange: preview }); 
});
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
</style>
</head>
<body>
<table width="100%" cellpadding="5" cellspacing="0" border="0">
	<tr>
    	<td align="left"><h1>Image Manager</h1></td>
    </tr>
	<?php
        if(isset($_REQUEST['ValidateUploadImageFlag']) && $_REQUEST['ValidateUploadImageFlag']=='true')
        {
            $Extension = $Web->GetExtension($_FILES['ImageFile']['name']);
            if($Extension=='jpg' || $Extension=='png' || $Extension=='gif')
            {
                $FileName = $Web->uploadfile($_FILES['ImageFile'], "../".ORIGINAL_IMAGES, $Extension);
				$_SESSION['NewFileName']=$FileName;
                $ImagePath = "../".ORIGINAL_IMAGES."/".$FileName;
                
                $_SESSION['OriginalImagePath'] = $ImagePath;
                $_SESSION['ThumbImagePath'] = "../".$_SESSION['Folder']."/".$FileName;
                
                $_SESSION['OriginalWidth'] = $Web->getWidth($ImagePath);
                $_SESSION['OriginalHeight'] = $Web->getHeight($ImagePath);
    ?>
    <tr>
    	<td>
        	<div align="left"><strong>Create Thumbnail</strong></div>
        </td>
    </tr>
    <tr>
    	<td>
        	<img src="<?php echo $ImagePath; ?>" style="float: left; margin-right: 10px;" id="thumbnail" alt="Create Thumbnail" />
        	<div style="border:1px #e5e5e5 solid; float:left; position:relative; overflow:hidden; width:<?php echo $_SESSION['ThumbWidth']; ?>px; height:<?php echo $_SESSION['ThumbHeight']; ?>px;">
            	<img src="<?php echo $ImagePath; ?>" style="position: relative; text-indent:100000000;" alt="Thumbnail Preview" />
            </div>
        </td>
    </tr>
    <tr>
    	<td align="left">
            <form name="thumbnail" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
            	<input type="hidden" name="x1" value="" id="x1" />
                <input type="hidden" name="y1" value="" id="y1" />
                <input type="hidden" name="x2" value="" id="x2" />
                <input type="hidden" name="y2" value="" id="y2" />
                <input type="hidden" name="w" value="" id="w" />
                <input type="hidden" name="h" value="" id="h" />
                <input type="submit" name="upload_thumbnail" value="Create Thumbnail" id="save_thumb" />
                <input type="hidden" name="Width" id="Width" value="<?php echo $_REQUEST["Width"]; ?>" />
                <input type="hidden" name="Height" id="Height" value="<?php echo $_REQUEST["Height"]; ?>" />
                <input type="hidden" name="ImageName" id="ImageName" value="<?php echo $FileName; ?>" />
            </form>
        </td>
    </tr>
    <?php
			}
			else
			{
				$ErrorMsg = 'Only jpg, gif and png images allowed';
			}
			echo '<hr />';
		}
		if(isset($ErrorMsg))
		{
			echo '<div align="left" class"ErrorMsg">'.$ErrorMsg.'</div>';
		}
	?>
    <tr>
    	<td>
        	<form name="UploadImageForm" id="UploadImageForm" method="post" action="" onsubmit="return ValidateImage();" enctype="multipart/form-data">
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
<script>
function preview(img, selection)
{
	var scaleX = <?php echo $_SESSION['ThumbWidth']; ?> / selection.width;
	var scaleY = <?php echo $_SESSION['ThumbHeight']; ?> / selection.height; 
	
	$('#thumbnail + div > img').css({ 
		width: Math.round(scaleX * <?php echo $_SESSION['OriginalWidth']; ?>) + 'px', 
		height: Math.round(scaleY * <?php echo $_SESSION['OriginalHeight']; ?>) + 'px',
		marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px', 
		marginTop: '-' + Math.round(scaleY * selection.y1) + 'px' 
	});
	$('#x1').val(selection.x1);
	$('#y1').val(selection.y1);
	$('#x2').val(selection.x2);
	$('#y2').val(selection.y2);
	$('#w').val(selection.width);
	$('#h').val(selection.height);
}
</script>
</body>
</html>
<?php
}
?>