<?php
	require_once 'forum_sso_functions.php';

	// Your code to delete the user from your website goes here

  $userId = '';
  deleteForumUser($userId);

	header("Location: index.php");
	exit();
?>
