<?php
# This file contains functions for the websitetoolbox.com forum single sign on.

# Replace USERNAME with your Website Toolbox username. If you are using a managed domain or a subdomain, use that instead of USERNAME.websitetoolbox.com. 
define("HOST","USERNAME.websitetoolbox.com");
# Get The API Key from the Settings -> Single Sign On section of the Website Toolbox admin area.
define("API_KEY","rzbHTaTbCeO");

# Initializing session if it is not started in client project files to assign SSO login authtoken into $_SESSION['authtoken']. The $_SESSION['authtoken'] is used in forumSignout function to logout from websitetoolbox forum.
# Checking current session status if it is not exists in client project files then session will be started.
if (!$_SESSION) {session_start();}

#Purpose: Function for registering a new user on websitetoolbox forum. 
#parmeter: Param $user an array containing information about the new user. The array user will contain mandatory values (member, pw and email) which will be used to build URL query string to register a new user on websitetoolbox forum. The array $user can also contain optional value such as name, avatar, profile picture etc.
# URL with all parameter from $user array passed in doHTTPCall function to create a request using curl or file and getting response from the Website Toolbox forum.
#return: "Registration Complete" or error response string from Website Toolbox.
function forumSignup($user) {
	# Changes the case of all keys in an array
	$user = array_change_key_case($user);	
	foreach ($user as $key => $value) {
	  if ($value === NULL)
		 $user[$key] = '';
	}
	# Generating a URL-encoded query string from the $user array.	
	$parameters = http_build_query($user, NULL, '&');   
	$URL = "/register/create_account?type=json&apikey=".API_KEY."&".$parameters;
	# making a request using curl or file and getting response from the Website Toolbox.
	$response = doHTTPCall($URL);
	$json_response = json_decode($response);
	if($json_response->{'userid'}) {
		return "Registration Complete";
	} else {
		return $json_response->{'message'};
	}
}


# Purpose: function for sign in on websitetoolbox forum if username exist on your website as well as on Website Toolbox forum.
# parmeter: Param $user an array containing information about the currently signed on user. The array user will contain mandatory (user) value which passed with apikey in request URL.
# URL with user and apikey parameter passed in doHTTPCall function to create a request using curl or file and return authtoken from the Website Toolbox forum.
# Assigned authtoken into $_SESSION['authtoken'].  
# The returned authtoken is checked for null. If it's not null then loaded with "register/dologin?authtoken" url through IMG src to sign in on websitetoolbox forum.
# return: "Login Successful" or an error response string from Website Toolbox.
function forumSignin($user) {
	# Changes the case of all keys in an array
	$user = array_change_key_case($user);	
	foreach ($user as $key => $value) {
	  if ($value === NULL)
		 $user[$key] = '';
	}
	# Generating a URL-encoded query string from the $user array.	
	$login_parameters = http_build_query($user, NULL, '&');
	# user details stored in session which will used later in forumSignout function. 
	$_SESSION['login_parameters'] = $login_parameters;
	$URL = "/register/setauthtoken?type=json&apikey=".API_KEY."&".$login_parameters;
	# making a request using curl or file and getting response from the Website Toolbox.
	$response = doHTTPCall($URL);
	$json_response = json_decode($response);

	# Check authtoken for null. If authtoken not null then load with "register/dologin?authtoken" url through IMG src to sign in on websitetoolbox forum.
	if ($json_response->{'authtoken'}) {
		# potentially also store $json_response->{'userid'} in your database for later use
		
		$_SESSION['authtoken'] = $json_response->{'authtoken'};
		echo "<img src='//".HOST."/register/dologin?authtoken=".$json_response->{'authtoken'}."' border='0' width='1' height='1' alt=''>";
		# You can optionally redirect or link to http://".HOST."/?authtoken=$json_response->{'authtoken'} instead of using the IMG tag, 
		# or you can use both because the IMG tag will fail in browsers that block third-party cookies if a subdomain isn't being used.
		return "Login Successful";	
	} else {
		return $json_response->{'message'};
	}
}
#Purpose: function for sign out from websitetoolbox forum.
# It check for $_SESSION['authtoken'] if it's not null then the "register/logout?authtoken" is loaded with IMG src to logout user from websitetoolbox forum.
# Reset authtoken session variable $_SESSION['authtoken'] after successful sign out.
# return: the function will return sign out status message as "Logout Successful" or "Logout Failed" from websitetoolbox forum.
function forumSignout() {
	# Check for authtoken value. If authtoken not null then load /register/logout?authtoken url through IMG src to sign out from websitetoolbox forum.
	if($_SESSION['authtoken']) {
		echo "<img src='//".HOST."/register/logout?authtoken=".$_SESSION['authtoken']."' border='0' width='1' height='1' alt=''>";
		# Reset authtoken session variable after sign out.
		$_SESSION['authtoken'] = '';
		return "Logout Successful";	
	} else {
		# If authtoken is missing from session variable then making a HTTP request using curl and getting authtoken from the Website Toolbox. 
		# Passing user details via $_SESSION['login_parameters'] which stored in session during user login.
		# If authtoken not null then the "register/logout?authtoken" is loaded with IMG src to logout user from websitetoolbox forum and return sign out status message as "Logout Successful"
		# If authtoken returned as null then appropriate error message will be returned. 
		$URL = "/register/getauthtoken?type=json&apikey=".API_KEY."&".$_SESSION['login_parameters'];
		$response = doHTTPCall($URL);
		$json_response = json_decode($response);
		if($json_response->{'authtoken'}) {
			echo "<img src='//".HOST."/register/logout?authtoken=".$json_response->{'authtoken'}."' border='0' width='1' height='1' alt=''>";
			return "Logout Successful";
		} else {
			return $json_response->{'message'};
		}		
	}	
}

#Purpose: Create a request using curl and getting response from the Website Toolbox.
#parmeter: request URL which will use to make curl request to websitetoolbox forum.
#return: return response from the Website Toolbox forum.
function doHTTPCall($URL){
	/* Sent HTTP request on the website Toolbox from from your sever. */
	$ch = curl_init("http://".HOST.$URL);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec($ch);      
	curl_close($ch);
	return $response;
}

?>
