<?php
require_once 'forum_sso_functions.php';

if (isset($_GET['action']) && $_GET['action'] == 'signup') {
  // Your code to create the user's account on your website goes here

  // This is optional. If you provide an email during log in, it will automatically create the user's account if it doesn't already exist.
  // See the comment in login.php

  $user = array();
  $user['member'] = $_POST['username'];
  $user['pw'] = $_POST['password'];
  $user['email'] = $_POST['email'];
  $user['name'] = $_POST['name']; // optional
  $user['avatar'] = ""; // optional
  $user['usergroupid'] = ""; // optional
  $user['field42323'] = ""; // example custom profile field. optional

  \WTForum\createUser($user);
  \WTForum\storeAuthToken($user);
  header("Location: index.php?action=login");
  exit();
}
?>

<html>
<head>
	<title>Sign up</title>
</head>
<body>
	<h2>Sign up</h2>
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
  <p><input type="text" name="username" placeholder="Username"></p>
  <p><input type="text" name="password" placeholder="Password"></p>
  <p><input type="text" name="email" placeholder="Email"></p>
  <p><input type="text" name="name" placeholder="Name"></p>
  <input type="hidden" name="action" value="signup">
  <p><button type="submit">Sign up</button></p>
</form>
</body>
</html>
