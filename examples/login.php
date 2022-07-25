<?php
require_once 'forum_sso_functions.php';

if (isset($_POST['action']) && $_POST['action'] == 'login') {
  // Your code to log the user into your website goes here

  $user = array();
  $user['user'] = $_POST['username'];
  // If you want to auto-create the user when they don't already exist, include 'email' (ie: $user['email']).
  // Optionally also include pw, name, usergroupid, and custom profile fields.

  \WTForum\storeAuthToken($user);
  header("Location: index.php?action=login");
  exit();
}
?>

<html>
<head>
	<title>Log in</title>
</head>
<body>
	<h2>Log in</h2>
  <p>Navigation:</p>
  <?php include('header.php'); ?>
<form method="post">
  <p><input type="text" name="username" placeholder="Username or Email"></p>
  <p><input type="text" name="password" placeholder="Password"></p>
  <input type="hidden" name="action" value="login">
  <p><button type="submit">Log in</button></p>
</form>
</body>
</html>
