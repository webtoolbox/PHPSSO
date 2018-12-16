<?php
require_once 'forum_sso_functions.php';

if (isset($_GET['action']) && $_GET['action'] == 'update') {

  // Your code to update the user's account on your website goes here

  if (isset($_COOKIE['forum_userid'])) {
    $user = array();
    $user['username'] = $_POST['username'];
    $user['password'] = $_POST['password'];
    $user['email'] = $_POST['email'];
    $user['name'] = $_POST['name'];
    $user['avatarUrl'] = "";
    $user['userGroups'] = [];
    $user['customFields'] = [
      [
        "profileFieldId" => 42323,
        "value" => "string"
      ]
    ];

    \WTForum\updateUser($_COOKIE['forum_userid'], $user);
  }

  header("Location: index.php");
  exit();
}
?>

<html>
<head>
	<title>Account settings</title>
</head>
<body>
	<h2>Update account settings</h2>
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
  <input type="hidden" name="action" value="update">
  <p><button type="submit">Update</button></p>
</form>
</body>
</html>
