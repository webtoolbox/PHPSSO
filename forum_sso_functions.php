<?php
# This file contains functions used to set up Single Sign On between your Website Toolbox forum and your website
# See: https://www.websitetoolbox.com/support/single-sign-on-token-based-authentication-241

namespace WTForum;

# ie: https://USERNAME.discussion.community, https://forums.mysite.com, etc.
# This should not be an embed page URL.
define("WTForum\FORUM_DOMAIN","");

# Get The API Key from the Settings -> Single Sign On section of the Website Toolbox admin area.
define("WTForum\FORUM_API_KEY","");

# Only if you're using the forum embed code. ie: https://mysite.com/forum/
define("WTForum\FORUM_EMBED_PAGE","");

# Remember the user even after they close their browser?
define("WTForum\PERSISTENT_FORUM_SESSION","1");

// ------------------------------------------------------------------ //

function createUser ($user) {
	$response = httpRequest("/register/create_account", $user);
	if(!$response->{'userid'}) {
		error_log("Failed to create forum account: ". $response->{'message'});
	}
}

function storeAuthToken ($user) {
	$response = httpRequest("/register/setauthtoken", $user);
	if ($response->{'authtoken'}) {
		setForumCookie('forum_userid', $response->{'userid'});
		setForumCookie('authtoken', $response->{'authtoken'});
	} else {
		error_log("Failed to get forum authtoken: ". $response->{'message'});
	}
}

function logout () {
	// calling printLogoutImage() in your code is also required
	if(isset($_COOKIE['authtoken'])) {
		deleteCookie('authtoken');
		deleteCookie('forum_userid');
	}
}

// You don't need to use this if you're including the authtoken in the forum link on your website
// Due to third-party cookie blocking in some browsers, the image tag approach will only work in those browsers if you're using a custom domain name (ie: https://forums.yoursite.com)
function printLoginImage () {
	if(isset($_COOKIE['authtoken'])) {
		$url = getDomain()."/register/dologin?".getAuthParams();
		echo "<img src='$url' border='0' width='1' height='1' alt=''>";
	}
}

// You don't need to use this if you're redirecting the user to the forum log out page when they log out from your website. See examples/logout.php
// Due to third-party cookie blocking in some browsers, the image tag approach will only work in those browsers if you're using a custom domain name (ie: https://forums.yoursite.com)
function printLogoutImage () {
	if(isset($_GET['authtoken'])) {
		echo "<img src='".getDomain()."/register/logout?authtoken=".$_GET['authtoken']."' border='0' width='1' height='1' alt=''>";
	}
}

function getDomain () {
	$forumDomain = FORUM_DOMAIN;
	if (!preg_match("#^https?://#i", $forumDomain)) {
		$forumDomain = "http://".$forumDomain;
	}
	if (preg_match("#^https?://.+/.+#i", $forumDomain)) {
		error_log("Invalid forum address provided in SSO code.");
	}
	return $forumDomain;
}

function getEmbedPage () {
	$forumEmbedPage = FORUM_EMBED_PAGE;
	if ($forumEmbedPage) {
		if (!preg_match("#^https?://#i", $forumEmbedPage)) {
			$forumEmbedPage = "http://".$forumEmbedPage;
		}
	}
	return $forumEmbedPage;
}

function getAddress () {
	if (FORUM_EMBED_PAGE) {
		$forumAddress = getEmbedPage();
	} else {
		$forumAddress = getDomain();
	}
	if (isset($_COOKIE['authtoken'])) {
		if (preg_match("#\?#", $forumAddress)) {
			$forumAddress .= "&";
		} else {
			$forumAddress .= "?";
		}
		$forumAddress .= getAuthParams();
	}
	return $forumAddress;
}

function getAuthParams () {
	$params = "";
	if (isset($_COOKIE['authtoken'])) {
		$params .= "authtoken=".$_COOKIE['authtoken'];
	}
	if (PERSISTENT_FORUM_SESSION) {
		$params .= "&remember=1";
	}
	return $params;
}

function httpRequest($path, $user){
	if (preg_match("#\?#", $path)) {
		$path .= "&";
	} else {
		$path .= "?";
	}

	# Generating a URL-encoded query string from the $user array.
	$parameters = '';
	if ($user) {
		$user = array_change_key_case($user);
		foreach ($user as $key => $value) {
			if ($value === NULL)
			 $user[$key] = '';
		}
		$parameters = http_build_query($user, "", '&');
	}

	$url = getDomain().$path."type=json&apikey=".FORUM_API_KEY."&".$parameters;
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec($curl);
	curl_close($curl);
	$namedArray = json_decode($response);
	return $namedArray;
}

function setForumCookie ($name, $value) {
	if (PERSISTENT_FORUM_SESSION) {
		$expiration = time()+60*60*24*365*5;
	} else {
		$expiration = 0;
	}
	setcookie($name, $value, $expiration, "/", $_SERVER['HTTP_HOST']);
}

function deleteCookie ($name) {
	if (isset($_COOKIE[$name])) {
		setcookie($name, '', 1, "/");
		unset($_COOKIE[$name]);
	}
}

// API functions: https://www.websitetoolbox.com/api/

function getUser ($userId) {
	$user = apiRequest('GET', "/users/$userId", NULL);
	return $user;
}

function updateUser ($userId, $data) {
	$user = apiRequest('POST', "/users/$userId", $data);
	return $user;
}

function deleteUser ($userId) {
	$response = apiRequest('DELETE', "/users/$userId", NULL);
	if (isset($_COOKIE['forum_userid']) && $userId == $_COOKIE['forum_userid']) {
		deleteCookie('authtoken');
		deleteCookie('forum_userid');
	}
	return $response;
}

function apiRequest ($method, $path, $data){
	$url = "https://api.websitetoolbox.com/v1/api$path";
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	      "x-api-key: ".FORUM_API_KEY,
		  'Content-Type: application/json',
	   ));
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	if (strtoupper($method) == "POST") {
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data));
	} else if (strtoupper($method) == "DELETE") {
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
	}
	$response = curl_exec($curl);
	curl_close($curl);
	$namedArray = json_decode($response);
	return $namedArray;
}

?>
