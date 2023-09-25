$(document).on('submit', '#PaymentForm', function(){
	$('#PaymentReturnMsg').html('Please wait...');
	var Card	= $('#Card').val();
	var Em		= $('#ExpMonth').val();
	var Ey		= $('#ExpYear').val();
	var Code	= $('#CVCCode').val();
	var AmountP	= $('#Amount').val();
	$('input, select').attr('readonly', 'readonly');
	
	// Send the card details to White; get back a token
	White.createToken({
		key: 'pk_test_cd42a4b286212dc045b5fda872514457', // your publishable key
		card: {
			number: Card,
			exp_month: Em,
			exp_year: Ey,
			cvc: Code
		},
		amount: AmountP,
		currency: 'AED'
	}, function(status, response) {
		$.post(WEB_URL+'complete-payment.php?Token='+response.id, null, function(Data, textStatus){
			
			$('#PaymentReturnMsg').html(Data.Msg);
			
			if(Data.Error=='1')
				$('input, select').removeAttr('readonly');
				
			if(Data.ReturnUrl!='')
				window.location.href=Data.ReturnUrl;
		}, 'json');
		// Complete
		if(response.error) {
			// Something went wrong
			//alert(response.error.message);
			$('#PaymentReturnMsg').html('Wrong card details. Please try again!');
			$('input, select').removeAttr('readonly');
			return false;
		}
	});
	return false;
});