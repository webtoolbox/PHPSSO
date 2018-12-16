<?php
require_once 'forum_sso_functions.php';

if (isset($_GET['action']) && $_GET['action'] == 'login') {
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
  <ul>
    <li><a href="index.php">Home</a></li>
    <li><a href="<?php echo \WTForum\getAddress(); ?>">Forum</a></li>
    <li><a href="signup.php">Sign up</a></li>
    <li><a href="login.php">Log in</a></li>
    <li><a href="logout.php">Log out</a></li>
    <li><a href="account.php">Account</a></li>
    <li><a href="delete.php">Delete</a></li>
  </ul>
<form method="post">
  <p><input type="text" name="username" placeholder="Username or Email"></p>
  <p><input type="text" name="password" placeholder="Password"></p>
  <input type="hidden" name="action" value="login">
  <p><button type="submit">Log in</button></p>
</form>
</body>
</html>
