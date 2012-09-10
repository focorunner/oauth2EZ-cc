<?php
/*a
This PHP script is designed to demonstrate Oauth 2.0 with Constant Contact with as little code as possible.
This example sets a cookie to store the token, and only starts the authentication flow if the the cookie 
doesn't exist, but the same thing can be accomplished with a session variable.
*/

// SET THESE VALUES
$api_key = "14da8af3-fa13-4b78-9cb8-221260bf898e"; // From developer.constantcontact.com
$client_secret = "95bdf29962bc4299af06da8d6ca37bc2"; // From developer.constantcontact.com
$redirect_uri = urlencode('http://localhost:8080/oauth2ez/auth.php'); // Must match redirect uri for API Key, as set on developer.constantcontact.com

// If already authenticated (authentication cookie exists), welcome user echo logout link
if (isset($_COOKIE['ctctauth'])) {
	echo '<strong>You are Authenticated. Welcome Back!</strong> <small>( <a href="deauth.php">log out</a> )</small><br>';
	}

// If not authenticated (no cookie)...
else {
	if (isset($_GET['code']) && !empty($_GET['code'])) {
		
		// Call for access token...
		$returns = file_get_contents("https://oauth2.constantcontact.com/oauth2/oauth/token?grant_type=authorization_code&client_id=".$api_key."&client_secret=".$client_secret."&code=".$_GET['code']."&redirect_uri=".$redirect_uri);
		$oauthdata = json_decode($returns);

		// Set  cookie containing access token (could make it an array cookie with username and access token)
		setcookie("ctctauth",$oauthdata->{'access_token'},time() + ($oauthdata->{'expires_in'}));
		
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