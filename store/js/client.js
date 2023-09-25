// Return today's date and time
var currentTime = new Date()

// returns the month (from 0 to 11)
var CurrentMonth = currentTime.getMonth() + 1;
if(CurrentMonth==12)
	var NextMonth = 1;
else
	var NextMonth = CurrentMonth+1;

// returns the day of the month (from 1 to 31)
var CurrentDay = currentTime.getDate()

// returns the year (four digits)
var CurrentYear = currentTime.getFullYear();

var MinDate = CurrentYear+'/'+CurrentMonth+'/'+CurrentDay;
if(CurrentMonth==12)
{
	CurrentYear++;
}
var MaxDate = CurrentYear+'/'+NextMonth+'/'+CurrentDay;

$(document).ready(function(){
	var WWidth = $(window).width();
	if(WWidth<=768)
	{
		var WHeight = $(window).height();
		$('.categories-ul ul#menu').css('max-height', WHeight+'px');
		
		$('.categories-ul').append('<a class="close-categories-ul">x</a><div class="clear"></div>');
		$('.categories-list div.heading').click(function(){
			$('.categories-ul').addClass('categories-ul-show');
			$('body').css('overflow-y', 'hidden');
		});
	}
	$('.close-categories-ul').click(function(){
		$('.categories-ul').removeClass('categories-ul-show');
		$('body').css('overflow-y', 'auto');
	});
	$('#pageBanners').skdslider({
		delay:5000,
		fadeSpeed: 2000,
		animationSpeed: 2000,
		showNextPrev:true,
		showPlayButton:false,
		autoSlide:true,
		animationType:'sliding'
	});
	$('.pImagesList').carouFredSel({
		prev: '#pi-prev',
		next: '#pi-next',
		auto: false,
		//pagination: "#pager",
		scroll : {
			items : 1
		},
		circular:false
	});
	
	jQuery('.logo, .top-header, .banners, footer, .subscription-box, .item').viewportChecker({
		classToAdd: 'visible animated fadeIn',
		offset: 100
	});
	
	jQuery('.main-categories').viewportChecker({
		classToAdd: 'visible animated bounceInRight',
		offset: 100
	});
	
	$(".available select, #PaymentForm select").selectOrDie();
	$('#PaymentForm select').css('width', '100%');
	
	$(document).on('click', '.productdetaillink', function(){
		var Link = $(this).attr('data-link');
		window.location.href = Link;
	});
	/*
	$(document).click(function(){
		alert("asdasd");
		window.location.href=$(this).attr('data-link');
	});
	*/
	
	$('#ContactForm').submit(function(){
		$('#ContactFormFlag').val('true');
		var Form = $('#ContactForm').serialize();
		$('input, textarea').attr('disabled', 'disabled');
		$('#ErrorMsg').html('Please wait...');
		$.post(WEB_URL+"fireaction.php?refreshAjax="+Math.random(), Form, function(Data, textStatus){
			$('#ErrorMsg').html(Data.Msg);
			setTimeout("window.location.href='"+WEB_URL+"';", 3000);
		}, 'json');
		return false;
	});
	
	$('#AddToCartForm').submit(function(){
		$('#CartMsg').html('Please wait...');
		$('#AddToCartFlag').val('true');
		var Form = $('#AddToCartForm').serialize();
		$('#AddToCartForm input').attr('disabled');
		$.post(WEB_URL+"fireaction.php?refreshAjax="+Math.random(), Form, function(Data, textStatus){
			$('#CartMsg').html(Data.Msg);
			$('.jqiclose').css('display', '');
			if(Data.Error==0)
				$('#CartCount').html(Data.CartCount);
				
			$('#AddToCartForm input').removeAttr('disabled');
			//setTimeout("ClosePopUpWindow('jqi');", 1000);
		}, 'json');
		return false;
	});
	
	$('#LoginForm').submit(function(){
	
		$('#LoginErrorMsg').html('Please wait...');
		$('#LoginFormFlag').val('true');
		var Form = $('#LoginForm').serialize();
		
		//$('#LoginForm input').attr('disabled');
		$.post(WEB_URL+"fireaction.php?refreshAjax="+Math.random(), Form, function(Data, textStatus){
			if(Data.Error==0)
			{
				window.location.href=Data.Url;
			}
			else
			{
				$('#LoginErrorMsg').html(Data.Msg);
				$('#LoginForm input').removeAttr('disabled');
			}
		}, 'json');
		return false;
	});
	$('#RegisterForm').submit(function(){
		ValdiateRegisterForm();
		return false;
	});
	$('#ChangePasswordForm').submit(function(){
		ValidateChangePassword();
		return false;
	});
	
	$('#PreferredDate').datetimepicker({
		lang:'en',
		timepicker:false,
		format:'F d Y',
		formatDate:'Y/m/d',
		minDate:MinDate, // yesterday is minimum date
		yearStart:CurrentYear,
		yearEnd:CurrentYear,
	});
	$('#PreferredTime').datetimepicker({
		datepicker:false,
		format:'h:i a',
		formatTime:'h:i a',
		step:30,
		minTime:'09:00 am',
		maxTime:'08:30 pm',
		onShow:function() {
			$('.xdsoft_disabled').css('display', 'none');
		}
	});
	
	$('#SubscriptionForm').submit(function(){
		$('#SubscriptionFormFlag').val('true');
		var Form = $('#SubscriptionForm').serialize();
		$('#SubscriptionForm input').attr('disabled');
		$.post(WEB_URL+"fireaction.php?refreshAjax="+Math.random(), Form, function(Data, textStatus){
			$('.notification').html(Data.Msg).addClass('show-notification');
			$('#SubscriptionForm input').removeAttr('disabled');
			setTimeout('$(".notification").removeClass("show-notification");', 3000);
		}, 'json');
		return false;
	});
	
	//$('title').html($(window).width());
});
/*
$(window).resize(function(){
	$('title').html($(window).width());
});
*/

function ValdiateRegisterForm()
{
	with(document.RegisterForm)
	{
		if(ConfirmPassword.value!=RegisterPassword.value)
		{
			$('#RegisterErrorMsg').html('Passwords does not match');
			return false;
		}
		else if($('#EmailError').val()=='-1')
		{
			$('#RegisterErrorMsg').html('Validating email address. Please wait...');
			return false;
		}
		else if($('#EmailError').val()=='1')
		{
			$('#RegisterErrorMsg').html('Email address already exists. Please try again');
			return false;
		}
		else
		{
			$('#RegisterErrorMsg').html('Please wait...');
			RegisterFormFlag.value='true';
			var Form  = $('#RegisterForm').serialize();
			$('#RegisterForm input').attr('disabled');
			$.post(WEB_URL+"fireaction.php?refreshAjax="+Math.random(), Form, function(Data, textStatus){
				if(Data.Error=='0')
				{
					window.location.href = WEB_URL+'thank-you.html';
				}
				else
				{
					$('#RegisterForm input').removeAttr('disabled');
					$('#RegisterErrorMsg').html(Data.Msg);
				}
			}, 'json');
		}
	}
	return false;
}

function ValidateEmail(EmailAddress)
{
	if(EmailAddress!='')
	{
		$('#EmailError').val('-1');
		//$('#EmailErrorMsg').html('Checking email address...');
		$.post(WEB_URL+"fireaction.php?ValidateEmail=true&Email="+EmailAddress+"&refreshAjax="+Math.random(), null, function(Data, textStatus){
			$('#EmailError').val(Data.Error);
		}, 'json');
	}
}

function ValidateChangePassword()
{
	with(document.ChangePasswordForm)
	{
		if(NewPassword.value!=ConfirmPassword.value)
		{
			$('#ChangePasswordErrorMsg').html('Your password does not match');
			ConfirmPassword.focus();
			return false;
		}
		else
		{
			ChangePasswordFlag.value='true';
			var Form = $('#ChangePasswordForm').serialize();
			$.post(WEB_URL+"fireaction.php?refreshAjax="+Math.random(), Form, function(Data, textStatus){
				$('#ChangePasswordErrorMsg').html(Data.Msg);
			}, 'json');
		}
	}
	return false;
}

function DeleteItem(CartID)
{
	$.post(WEB_URL+"fireaction.php?DeleteCart=true&CartID="+CartID+"&refreshAjax="+Math.random(), null, function(Data, textStatus){
		//$('#Cart_'+CartID).remove();
		location.reload();
	}, 'json');
}

function UpdateCart(ToDo, CartID)
{
	$.post(
	WEB_URL+"fireaction.php?UpdateCart=true&ToDo="+ToDo+"&CartID="+CartID+"&refreshAjax="+Math.random(),
	 null, 
	 function(Data, textStatus){
		if(Data.Error==0)
		{
		location.reload();
		}
	}, 'json');
}

$(document).on('submit', '#CheckoutForm', function(){
	with(document.CheckoutForm)
	{
		$('#CheckoutErrorMsg').html('Please wait...');
		CheckoutFormFlag.value='true';
		var Form = $('#CheckoutForm').serialize();
		$('#CheckoutForm input').attr('disabled');
		
		if(DiscountCode.value!='')
		{
			$.post(WEB_URL+"fireaction.php?ValidateDicsount=true&Code="+DiscountCode.value+"&refreshAjax="+Math.random(), null, function(Data, textStatus){
				if(Data.Error=='1')
				{
					$('#DiscountMsg').html(Data.Msg);
				}
				else
				{
					$('#CheckoutErrorMsg').html(Data.Msg);
					$.post(WEB_URL+"fireaction.php?refreshAjax="+Math.random(), Form, function(Data, textStatus){
						window.location.href=Data.Url;			
					}, 'json');
					
				}
			}, 'json');
		}
		else
		{
			$.post(WEB_URL+"fireaction.php?refreshAjax="+Math.random(), Form, function(Data, textStatus){
				window.location.href=Data.Url;
			}, 'json');
		}
	}
	return false;
});
/*
function ValidateCheckoutForm()
{
	with(document.CheckoutForm)
	{
		$('#CheckoutErrorMsg').html('Please wait...');
		CheckoutFormFlag.value='true';
		var Form = $('#CheckoutForm').serialize();
		$('#CheckoutForm input').attr('disabled');
		
		if(DiscountCode.value!='')
		{
			$.post(WEB_URL+"fireaction.php?ValidateDicsount=true&Code="+DiscountCode.value+"&refreshAjax="+Math.random(), null, function(Data, textStatus){
				if(Data.Error=='1')
				{
					$('#DiscountMsg').html(Data.Msg);
				}
				else
				{
					$('#CheckoutErrorMsg').html(Data.Msg);
					$.post(WEB_URL+"fireaction.php?refreshAjax="+Math.random(), Form, function(Data, textStatus){
						$('#CheckoutErrorMsg').html(Data);
						$('#paypalForm').submit();
					});
					
				}
			}, 'json');
		}
		else
		{
			$.post(WEB_URL+"fireaction.php?refreshAjax="+Math.random(), Form, function(Data, textStatus){
				$('#CheckoutErrorMsg').html(Data);
				$('#paypalForm').submit();
			});
		}
	}
	return false;
}
*/