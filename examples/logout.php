<?php
	require_once 'forum_sso_functions.php';

	// Your code to log the user out of your website goes here

	$logoutURL = '';
	if (isset($_COOKIE['authtoken'])) {
		// redirect the user to the page that logs them out of the forum and then redirects them to the home page (or any other page that you want)
		$returnURL = urlencode("https://yoursite.com/index.php?action=logout&authtoken=".$_COOKIE['authtoken']);
		$logoutURL = \WTForum\getDomain() . "/register/logout?authtoken=".$_COOKIE['authtoken']."&redirect=$returnURL";
		\WTForum\logout();
	} else {
		$logoutURL = "index.php?action=logout";
	}

	header("Location: $logoutURL");
	exit();
?>
