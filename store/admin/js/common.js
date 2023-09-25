ddsmoothmenu.init({
	mainmenuid: "TopMenu", //menu DIV id
	orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu', //class added to menu's outer DIV
	//customtheme: ["#1c5a80", "#18374a"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})

$(document).ready(function(){
	//$('.Login-Box').corner();
});

function DeleteData(RecordID, Flag)
{
	$.post("fireaction.php?"+Flag+"=true&PageType=Delete&RecordID="+RecordID+"&refreshAjax="+Math.random(), null, function(data){
		$('#Data_'+RecordID).remove();
		location.reload();
	});
}

function ValidateLoginForm(Value)
{
	with(document.LoginForm)
	{
		if(Username.value=='')
		{
			alert("Please enter username");
			Username.focus();
			return false;
		}
		else if(Password.value=='')
		{
			alert("Please enter password");
			Password.focus();
			return false;
		}
		Trigger.value=Value;
	}
	return true;
}

function CheckExtension(filename)
{
	var parts = filename.split('.');
	var extension = parts[parts.length-1];
	return extension.toLowerCase();
}

function UploadSong(Type, RecordID)
{
	var w=screen.width;
	var h=screen.height;
	var LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
	var TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
	
	var settings = 'height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars=1,resizable=0';
	var MyWindow = window.open("upload_file.php?Type="+Type+"&RecordID="+RecordID, "UploadFile", settings);
	MyWindow.moveTo(0, 0);
}

function UploadImage(Width, Height, Folder, Type, RecordID, TableName, FieldName)
{
	var w=screen.width;
	var h=screen.height;
	
	var LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
	var TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
	$.post("set_photo_session.php?Width="+Width+"&Height="+Height+"&Folder="+Folder+"&ImageType="+Type+"&RecordID="+RecordID+"&TableName="+TableName+"&FieldName="+FieldName+"&refreshAjax="+Math.random(), null, function(data){
		var settings = 'height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars=1,resizable=0';
		var MyWindow = window.open("upload_photo.php", "UploadImage", settings);
		MyWindow.moveTo(0, 0);
	});
}

function UploadImageWOCrop(Width, Height, Folder, Type, RecordID, TableName, FieldName)
{
	var w=screen.width;
	var h=screen.height;
	
	var LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
	var TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
	$.post("set_photo_session.php?Width="+Width+"&Height="+Height+"&Folder="+Folder+"&ImageType="+Type+"&RecordID="+RecordID+"&TableName="+TableName+"&FieldName="+FieldName+"&refreshAjax="+Math.random(), null, function(data){
		var settings = 'height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars=1,resizable=0';
		var MyWindow = window.open("upload_photo_without_crop.php", "UploadImage", settings);
		MyWindow.moveTo(0, 0);
	});
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

function numbersonly(e, decimal)
{
	var key;
	var keychar;
	
	if(window.event)
	{
		key = window.event.keyCode;
	}
	else if(e)
	{
		key = e.which;
	}
	else
	{
		return true;
	}
	
	keychar = String.fromCharCode(key);
	
	if((key==null) || (key==0) || (key==8) ||  (key==9) || (key==13) || (key==27) )
	{
		return true;
	}
	else if ((("0123456789.").indexOf(keychar) > -1))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function SelectAll(Ele)
{
	var Total = $("input[name='"+Ele+"[]']").length;
	var Total_Selected = $("input[name='"+Ele+"[]']:checked").length;
	if(Total>Total_Selected)
		$("input[name='"+Ele+"[]']").attr('checked', 'checked');
	else
		$("input[name='"+Ele+"[]']").removeAttr('checked');
}