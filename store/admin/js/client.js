$(document).ready(function(){
	$('#UpdateSequenceForm').submit(function(){
		var Form = $('#UpdateSequenceForm').serialize();
		$('input').attr('disabled', 'disabled');
		$.post("fireaction.php?refreshAjax="+Math.random(), Form, function(Data, textStatus){
			if(Data==1)
			{
				alert("Sequence updated successfully");
				$('input').removeAttr('disabled');
			}
		});
		return false;
	});
});

function ValidateDCode()
{
	with(document.RecordForm)
	{
		ValidateDCodeFlag.value='true';
	}
	return true;
}

function ValidateBanner()
{
	with(document.RecordForm)
	{
		if(Link.value=='')
		{
			alert('Please enter link');
			Link.focus();
			return false;
		}
		ValidateBannerFlag.value='true';
	}
	return true;
}

function ReloadBannerFields(Option)
{
	if(Option==1)
		$('.LargeData').css('display', '');
	else
		$('.LargeData').css('display', 'none');
}

function ValidateBannerData()
{
	with(document.RecordForm)
	{
		ValidateBannerDataFlag.value='true';
	}
	return true;
}

function DeleteImage(RecordID, Table, Field)
{
	$.post("fireaction.php?RecordID="+RecordID+"&Table="+Table+"&Field="+Field+"&DeleteImage=true&refreshAjax="+Math.random(), null, function(Data, textStatus){
		//$('#ImageDelete_'+RecordID).html('&nbsp;');
		//$('#ImageView_'+RecordID).html('&nbsp;');
		location.reload();
	});
}

function UpdateOrderStatus()
{
	$("#StatusMsg").css('display', 'none');
	var Form = $('#UpdateOrderStatusForm').serialize();
	$.post("fireaction.php?refreshAjax="+Math.random(), Form, function(Data, textStatus){
	if(Data.Error==0)
	{
		$('#StatusMsg').html(Data.Msg);
		$("#StatusMsg").fadeIn();
		setTimeout('$("#StatusMsg").fadeOut();', 3000);
	}
	else
	{
		$('#StatusMsg').html('Please try again.');
		$("#StatusMsg").fadeIn();
		setTimeout('$("#StatusMsg").fadeOut();', 3000);
	}
}, 'json');
}

function AddInType(Selected)
{
	$('#Tr_StartDate').css('display', 'none');
	$('#Tr_EndDate').css('display', 'none');
	$('#Tr_Price').css('display', 'none');
	
	if(Selected=='1')
	{
		$('#Tr_StartDate').css('display', '');
		$('#Tr_EndDate').css('display', '');
		$('#Tr_Price').css('display', '');
	}
}

function ValidateContent()
{
	with(document.ContentForm)
	{
		if(Title.value=='')
		{
			alert("Please enter title");
			Title.focus();
			return false;
		}
		ValidateContentFlag.value='true';
	}
	return true;
}

function ValidateBrand()
{
	with(document.RecordForm)
	{
		if(Title.value=='')
		{
			alert("Please enter title");
			Title.focus();
			return false;
		}
		ValidateBrandFlag.value='true';
	}
	return true;
}

function ValidateCategory()
{
	with(document.RecordForm)
	{
		if(ParentID.value=='')
		{
			alert("Please select parent category");
			ParentID.focus();
			return false;
		}
		else if(Title.value=='')
		{
			alert("Please enter title");
			Title.focus();
			return false;
		}
		ValidateCategoryFlag.value='true';
	}
}

function ValidateColor()
{
	with(document.RecordForm)
	{
		if(Title.value=='')
		{
			alert("Please enter color name");
			Title.focus();
			return false;
		}
		else if(ColorCode.value=='')
		{
			alert("Please select color");
			ColorCode.focus();
			return false;
		}
		ValidateColorFlag.value='true';
	}
	return true;
}

function ValidateSize()
{
	with(document.RecordForm)
	{
		if(Title.value=='')
		{
			alert("Please enter size");
			Title.focus();
			return false;
		}
		ValidateSizeFlag.value='true';
	}
	return true;
}

function WholeSaleOptions(Value)
{
	$('#Tr_StartDate').css('display', 'none');
	$('#Tr_EndDate').css('display', 'none');
	$('#Tr_Price').css('display', 'none');
		
	if(Value==1)
	{
		$('#Tr_StartDate').css('display', '');
		$('#Tr_EndDate').css('display', '');
		$('#Tr_Price').css('display', '');
	}
}

function ValidateProduct()
{
	with(document.RecordForm)
	{
		if(BrandID.value=='')
		{
			alert("Please select brand");
			BrandID.focus();
			return false;
		}
		else if(CategoryID.value=='')
		{
			alert("Please select category");
			CategoryID.focus();
			return false;
		}
		else if(Title.value=='')
		{
			alert("Please enter title");
			Title.focus();
			return false;
		}
		else if(ShortDesc.value=='')
		{
			alert("Please enter short description");
			ShortDesc.focus();
			return false;
		}
		else if(Price.value=='')
		{
			alert("Please enter price");
			Price.focus();
			return false;
		}
		else if(Stock.value=='')
		{
			alert("Please enter stock value");
			Stock.focus();
			return false;
		}
		ValidateProductFlag.value='true';
	}
	return true;
}
function ValidateChangePassword()
{
	with(document.RecordForm)
	{
		if(OldPassword.value=='')
		{
			alert("Please enter old password");
			OldPassword.focus();
			return false;
		}
		else if(NewPassword.value=='')
		{
			alert("Please enter new password");
			NewPassword.focus();
			return false;
		}
		else if(NewPassword.value.length<7)
		{
			alert("Password should have atleast 7 characters");
			NewPassword.focus();
			return false;
		}
		else if(ConfirmPassword.value=='')
		{
			alert("Please enter password again");
			ConfirmPassword.focus();
			return false;
		}
		else if(NewPassword.value!=ConfirmPassword.value)
		{
			alert("Passwords does not match. Try again");
			ConfirmPassword.focus();
			return false;
		}
		ChangePasswordFlag.value='true';
	}
	return true;
}