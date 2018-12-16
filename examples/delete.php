<?php
	require_once 'forum_sso_functions.php';

	// Your code to delete the user from your website goes here

	if (isset($_COOKIE['forum_userid'])) {
		\WTForum\deleteUser($_COOKIE['forum_userid']);
	}

	header("Location: index.php");
	exit();
?>
