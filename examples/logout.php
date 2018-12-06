<?php
	require_once 'forum_sso_functions.php';

	// Your code to log the user out of your website goes here

	header("Location: index.php?action=logout");
	exit();
?>
