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
#return: Parse and return user registration xml response "Registration Complete" or error response  from websitetoolbox forum.
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
	// decode the JSON data
	$json_response = json_decode($response);
	// Check for valid JSON
	if(is_object($json_response) && $json_response->{'userid'}) {
		return "Registration Complete";			
	} else {
		$response_xml = preg_replace_callback('/<!\[CDATA\[(.*)\]\]>/', 'filter_xml', $response);
		$response_xml = simplexml_load_string($response_xml);	
		$response = trim(htmlentities($response_xml->error));
		$full_length = strlen($response);	
		#Remove HTML tag with content from the message, return from forum if email of user already exist.
		if(strpos($response,'&lt;')) {
			$bad_string = strpos($response,'&lt;');
			$response = substr($response, 0, $bad_string-1);
		}
		# returning sso register response
		return $response;		  
	}
	
}


# Purpose: function for sign in on websitetoolbox forum if username exist on your website as well as on Website Toolbox forum.
# parmeter: Param $user an array containing information about the currently signed on user. The array user will contain mandatory (user) value which passed with apikey in request URL.
# URL with user and apikey parameter passed in doHTTPCall function to create a request using curl or file and return authtoken from the Website Toolbox forum.
# Assigned authtoken into $_SESSION['authtoken'].  
# The returned authtoken is checked for null. If it's not null then loaded with "register/dologin?authtoken" url through IMG src to sign in on websitetoolbox forum.
# return: Returns user's sign in status as "Login Successful" or "$response_xml->errormessage" from websitetoolbox forum.
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
	// decode json data
	$json_response = json_decode($response);
	// Check for valid JSON data
	if(is_object($json_response)) {					
		$authtoken = $json_response->{'authtoken'};	
		$error_message = $json_response->{'message'};
	} else {			
		$response = preg_replace_callback('/<!\[CDATA\[(.*)\]\]>/', 'filter_xml', $response);
		$response = simplexml_load_string($response);	
		$authtoken = htmlentities($response->authtoken);	
		$error_message = $response->errormessage;
	}		
	# Check authtoken for null. If authtoken not null then load with "register/dologin?authtoken" url through IMG src to sign in on websitetoolbox forum.
	if ($authtoken) {
		$_SESSION['authtoken'] = $authtoken;
		echo "<img src='//".HOST."/register/dologin?authtoken=$authtoken' border='0' width='1' height='1' alt=''>";
		# You can optionally redirect or link to http://".HOST."/?authtoken=$authtoken instead of using the IMG tag, 
		# or you can use both because the IMG tag will fail in browsers that block third-party cookies.
		return "Login Successful";	
	} else {
		return $error_message;
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
		// decode json data
		$json_response = json_decode($response);
		// Check for valid JSON data
		if(is_object($json_response)) {
			$authtoken = $json_response->{'authtoken'};	
			$errormessage = $json_response->{'message'};
		} else {
			$response = preg_replace_callback('/<!\[CDATA\[(.*)\]\]>/', 'filter_xml', $response);
			$response = simplexml_load_string($response);	
			$authtoken = htmlentities($response->authtoken);
			$errormessage = htmlentities($response->errormessage);
		}
		if($authtoken) {
			echo "<img src='//".HOST."/register/logout?authtoken=".$authtoken."' border='0' width='1' height='1' alt=''>";
			return "Logout Successful";
		} else {
			return $errormessage;
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

#Purpose: Function for filtering response xml
function filter_xml($matches) {
	return trim(htmlspecialchars($matches[1]));
} 
?>
