// This is called with the results from from FB.getLoginStatus().
function statusChangeCallback(response) {
	if(response.status === 'connected') {
		testAPI();
	} else if (response.status === 'not_authorized') {
		// The person is logged into Facebook, but not your app.
		document.getElementById('fbStatus').innerHTML = 'Click on this button to login with facebook';
	} else {
		// The person is not logged into Facebook, so we're not sure if
		// they are logged into this app or not.
		//document.getElementById('fbStatus').innerHTML = 'Please log ' + 'into Facebook.';
	}
}

// This function is called when someone finishes with the Login
// Button.  See the onlogin handler attached to it in the sample
// code below.
function checkLoginState() {
	$('.notification').html('Logging in! Please wait...').addClass('show-notification');
	FB.getLoginStatus(function(response) {
		statusChangeCallback(response);
	});
}

window.fbAsyncInit = function() {
	FB.init({
	appId      : '1617540228458090',
	cookie     : true,  // enable cookies to allow the server to access 
	// the session
	xfbml      : true,  // parse social plugins on this page
	version    : 'v2.2' // use version 2.1
});
/*
FB.getLoginStatus(function(response) {
	statusChangeCallback(response);
});
*/
};

// Load the SDK asynchronously
(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

// Here we run a very simple test of the Graph API after login is
// successful.  See statusChangeCallback() for when this call is made.
function testAPI() {
	FB.api('/me', function(response) {
		
		var UserID = response.id;
		var UserName = response.name;
		var UserEmail = response.email;
		
		$.post(WEB_URL+"fireaction.php?FbLogin=true&id="+UserID+"&name="+UserName+"&email="+UserEmail+"&refreshAjax="+Math.random(), null, function(Data, textStatus){
			window.location.href=Data.Url;
		}, 'json');
	});
}