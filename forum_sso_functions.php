<?php
# This file contains functions for the websitetoolbox.com forum single sign on.

# Replace USERNAME with your Website Toolbox username. If you are using a managed domain or a subdomain, use that instead of USERNAME.websitetoolbox.com. 
define("HOST","http://classiflyds.forumchatter.com");
# Get The API Key from the Settings -> Single Sign On section of the Website Toolbox admin area.
define("API_KEY","Tjl48kRwJ4u");

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
	$URL = "/register/create_account?apikey=".API_KEY."&".$parameters;
	# making a request using curl or file and getting response from the Website Toolbox.
	$response = doHTTPCall($URL);
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
	$URL = "/register/setauthtoken?apikey=".API_KEY."&".$login_parameters;
	# making a request using curl or file and getting response from the Website Toolbox.
	$response_xml = doHTTPCall($URL);
	$response_xml = preg_replace_callback('/<!\[CDATA\[(.*)\]\]>/', 'filter_xml', $response_xml);
	$response_xml = simplexml_load_string($response_xml);	
	$authtoken = htmlentities($response_xml->authtoken);
	# Check authtoken for null. If authtoken not null then load with "register/dologin?authtoken" url through IMG src to sign in on websitetoolbox forum.
	if ($authtoken) {
		$_SESSION['authtoken'] = $authtoken;
		echo "<img src='http://".HOST."/register/dologin?authtoken=$authtoken' border='0' width='1' height='1' alt=''>";
		return "Login Successful";	
	} else {
		return $response_xml->errormessage;
	}  	
}
#Purpose: function for sign out from websitetoolbox forum.
# It check for $_SESSION['authtoken'] if it's not null then the "register/logout?authtoken" is loaded with IMG src to logout user from websitetoolbox forum.
# Reset authtoken session variable $_SESSION['authtoken'] after successful sign out.
# return: the function will return sign out status message as "Logout Successful" or "Logout Failed" from websitetoolbox forum.
function forumSignout() {
	# Check for authtoken value. If authtoken not null then load /register/logout?authtoken url through IMG src to sign out from websitetoolbox forum.
	if($_SESSION['authtoken']) {
		echo "<img src='http://".HOST."/register/logout?authtoken=".$_SESSION['authtoken']."' border='0' width='1' height='1' alt=''>";
		# Reset authtoken session variable after sign out.
		$_SESSION['authtoken'] = '';
		return "Logout Successful";	
	} else {
		# If authtoken is missing from session variable then making a HTTP request using curl and getting authtoken from the Website Toolbox. 
		# Passing user details via $_SESSION['login_parameters'] which stored in session during user login.
		# If authtoken not null then the "register/logout?authtoken" is loaded with IMG src to logout user from websitetoolbox forum and return sign out status message as "Logout Successful"
		# If authtoken returned as null then appropriate error message will be returned. 
		$URL = "/register/getauthtoken?apikey=".API_KEY."&".$_SESSION['login_parameters'];
		$response_xml = doHTTPCall($URL);
		$response_xml = preg_replace_callback('/<!\[CDATA\[(.*)\]\]>/', 'filter_xml', $response_xml);
		$response_xml = simplexml_load_string($response_xml);	
		$authtoken = htmlentities($response_xml->authtoken);
		$errormessage = htmlentities($response_xml->errormessage);
		if($authtoken) {
			echo "<img src='http://".HOST."/register/logout?authtoken=".$authtoken."' border='0' width='1' height='1' alt=''>";
			return "Logout Successful";
		} else {
			return $errormessage;
		}		
	}	
}

#Purpose: Create a request using curl or file and getting response from the Website Toolbox.
#parmeter: request URL which will use to make curl request to websitetoolbox forum.
#return: return response from the Website Toolbox forum.
function doHTTPCall($URL){
	if (_checkBasicFunctions("curl_init,curl_setopt,curl_exec,curl_close")) {
		$ch = curl_init("http://".HOST.$URL);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);      
		curl_close($ch);
	} else if (_checkBasicFunctions("fsockopen,fputs,feof,fread,fgets,fclose")) {
		$fsock = fsockopen(HOST, 80, $errno, $errstr, 30);
		if (!$fsock) {
			echo "Error! $errno - $errstr";
		} else {
			$headers .= "POST $URL HTTP/1.1\r\n";
			$headers .= "HOST: ".HOST."\r\n";
			$headers .= "Connection: close\r\n\r\n";
			fputs($fsock, $headers);
			// Needed to omit extra initial information
			$get_info = false;
			while (!feof($fsock)) {
				if ($get_info) {
					$response .= fread($fsock, 1024);
				} else {
					if (fgets($fsock, 1024) == "\r\n") {
						$get_info = true;
					}
				}
			}
			fclose($fsock);
		}
	}
	return $response;
}

#Purpose: Function for filtering response xml
function filter_xml($matches) {
	return trim(htmlspecialchars($matches[1]));
} 

#Purpose: Check php basic functions exist or not
#parmeter: Accept parameter functionslist with values such as  'fsockopen,fputs,feof,fread,fgets,fclose'
function _checkBasicFunctions($functionList) {
	$functions = split(",",$functionList);
	foreach ($functions as $key=>$val) {
		$function = trim($val);
		if (!function_exists($function)) {
			return false;
		}
	}
	return true;
} 
?>
