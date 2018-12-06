<?php
# This file contains functions used to set up Single Sign On between your Website Toolbox forum and your website
# See: https://www.websitetoolbox.com/support/single-sign-on-token-based-authentication-241

# ie: https://USERNAME.discussion.community, https://forums.mysite.com, etc.
# This should not be an embed page URL.
define("FORUM_DOMAIN","");

# Get The API Key from the Settings -> Single Sign On section of the Website Toolbox admin area.
define("FORUM_API_KEY","");

# Only if you're using the forum embed code. ie: https://mysite.com/forum/
define("FORUM_EMBED_PAGE","");

# Remember the user even after they close their browser?
define("PERSISTENT_FORUM_SESSION","1");

// ------------------------------------------------------------------ //

function createForumUser ($user) {
	$response = forumHTTPRequest("/register/create_account", $user);
	if(!$response->{'userid'}) {
		error_log("Failed to create forum account: ". $response->{'message'});
	}
}

function storeForumAuthToken ($user) {
	$response = forumHTTPRequest("/register/setauthtoken", $user);
	if ($response->{'authtoken'}) {
		# potentially also store $response->{'userid'} in your database for later use (ie: using the API to delete a user or update their email address)
		if (!$_SESSION) {
			session_start();
		}
		$_SESSION['authtoken'] = $response->{'authtoken'};
	} else {
		error_log("Failed to get forum authtoken: ". $response->{'message'});
	}
}

function printLoginImage () {
	if(isset($_SESSION['authtoken'])) {
		$url = getForumDomain()."/register/dologin?authtoken=".$response->{'authtoken'};
		if (PERSISTENT_FORUM_SESSION) {
			$url .= "&remember=1";
		}
		echo "<img src='$url' border='0' width='1' height='1' alt=''>";
	}
}

function printLogoutImage () {
	if(isset($_SESSION['authtoken'])) {
		echo "<img src='".getForumDomain()."/register/logout?authtoken=".$_SESSION['authtoken']."' border='0' width='1' height='1' alt=''>";
		$_SESSION['authtoken'] = '';
	}
}

function getForumDomain () {
	$forumDomain = FORUM_DOMAIN;
	if (!preg_match("#^https?://#i", $forumDomain)) {
		$forumDomain = "http://".$forumDomain;
	}
	if (preg_match("#^https?://.+/.+#i", $forumDomain)) {
		error_log("Invalid forum address provided in SSO code.");
	}
	return $forumDomain;
}

function getForumEmbedPage () {
	$forumEmbedPage = FORUM_EMBED_PAGE;
	if ($forumEmbedPage) {
		if (!preg_match("#^https?://#i", $forumEmbedPage)) {
			$forumEmbedPage = "http://".$forumEmbedPage;
		}
	}
	return $forumEmbedPage;
}

function getForumAddress () {
	if (FORUM_EMBED_PAGE) {
		$forumAddress = getForumEmbedPage();
	} else {
		$forumAddress = getForumDomain();
	}
	if (isset($_SESSION['authtoken'])) {
		if (preg_match("#\?#", $forumAddress)) {
			$forumAddress .= "&";
		} else {
			$forumAddress .= "?";
		}
		$forumAddress .= "authtoken=".$_SESSION['authtoken'];
		if (PERSISTENT_FORUM_SESSION) {
			$forumAddress .= "remember=1";
		}
	}
	return $forumAddress;
}

function forumHTTPRequest($path, $user){
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
		$parameters = http_build_query($user, NULL, '&');
	}

	$url = getForumDomain().$path."type=json&apikey=".FORUM_API_KEY."&".$parameters;
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec($curl);
	curl_close($curl);
	$namedArray = json_decode($response);
	return $namedArray;
}

// API functions: https://www.websitetoolbox.com/api/

function getForumUser ($userId) {
	$user = forumApiGetRequest("/users/$userId");
	return $user;
}

function updateForumUser ($userId, $data) {
	$user = forumApiPostRequest("/users/$userId", $data);
	return $user;
}

function deleteForumUser ($userId) {
	$response = forumApiDeleteRequest("/users/$userId");
	return $response;
}

function forumApiGetRequest ($path){
	$url = "https://api.websitetoolbox.com/v1/api$path";
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	      "x-api-key: ".FORUM_API_KEY
	   ));
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec($curl);
	curl_close($curl);
	$namedArray = json_decode($response);
	return $namedArray;
}

function forumApiPostRequest ($path, $data){
	$url = "https://api.websitetoolbox.com/v1/api$path";
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	      "x-api-key: ".FORUM_API_KEY
	   ));
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec($curl);
	curl_close($curl);
	$namedArray = json_decode($response);
	return $namedArray;
}

function forumApiDeleteRequest ($path){
	$url = "https://api.websitetoolbox.com/v1/api$path";
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	      "x-api-key: ".FORUM_API_KEY
	   ));
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec($curl);
	curl_close($curl);
	$namedArray = json_decode($response);
	return $namedArray;
}

?>
