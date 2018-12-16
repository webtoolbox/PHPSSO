<?php
	require_once 'forum_sso_functions.php';

	// Your code to log the user out of your website goes here

	\WTForum\logout();

	header("Location: index.php?action=logout");
	exit();
?>
