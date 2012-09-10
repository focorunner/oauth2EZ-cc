<?php

/*

This PHP script is designed to demonstrate Oauth 2.0 with Constant Contact with as little code as possible.

This example sets a cookie to store the token, and only starts the authentication flow if the the cookie 

doesn't exist, but the same thing can be accomplished with a session variable.

*/



// SET THESE VALUES

$api_key = "YOUR_API_KEY"; // From developer.constantcontact.com

$client_secret = "YOUR_CLIENT_SECRET"; // From developer.constantcontact.com

$redirect_uri = 'YOUR_REDIRECT_URI'; // Must match redirect uri for API Key, as set on developer.constantcontact.com



// If already authenticated (authentication cookie exists), welcome user echo logout link

if (isset($_COOKIE['ctctauth'])) {

	echo '<strong>You are Authenticated. Welcome Back!</strong> <small>( <a href="deauth.php">log out</a> )</small><br>';
	echo 'Your access token is: '.$_COOKIE['ctctauth'];

}


// If not authenticated (no cookie)...

else {

	if (isset($_GET['code']) && !empty($_GET['code'])) {

		//echo $_GET['code']; die;

		// Call for access token...

		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://oauth2.constantcontact.com/oauth2/oauth/token?grant_type=authorization_code&client_id=".$api_key."&client_secret=".$client_secret."&code=".$_GET['code']."&redirect_uri=".$redirect_uri); 	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, '3');
		$returns = trim(curl_exec($ch));
		curl_close($ch);

		$oauth2data = json_decode($returns);



		if (isset($oauth2data->{'access_token'})) {
		setcookie("ctctauth",$oauth2data->{'access_token'},time() + ($oauth2data->{'expires_in'}));

		} else { echo $returns; }

		// Refresh page

		header('location: auth.php');

	}

	

	// If not an OAuth callback request, redirect to authroize/grant access with Constant Contact

	else {

		$codeurl = "https://oauth2.constantcontact.com/oauth2/oauth/siteowner/authorize?response_type=code&client_id=".$api_key."&redirect_uri=".$redirect_uri;

		header('location: '.$codeurl);

	}

}

?>