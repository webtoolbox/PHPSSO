<?php
require_once 'forum_sso_functions.php';

if (isset($_POST['action']) && $_POST['action'] == 'update') {

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
  <?php include('header.php'); ?>
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
