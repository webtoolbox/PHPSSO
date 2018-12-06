<?php
require_once 'forum_sso_functions.php';
?>
<html>
<head>
	<title>Home Page</title>
</head>
<body>
	<h2>Welcome to the home page!</h2>
  <p>Navigation:</p>
  <ul>
    <li><a href="index.php">Home</a></li>
    <li><a href="<?php echo getForumAddress(); ?>">Forum</a></li>
    <li><a href="signup.php">Sign up</a></li>
    <li><a href="login.php">Log in</a></li>
    <li><a href="logout.php">Log out</a></li>
    <li><a href="account.php">Account</a></li>
    <li><a href="delete.php">Delete</a></li>
  </ul>
<?php
  if (isset($_GET['action'])) {
    if ($_GET['action'] == 'logout') {
      printLogoutImage();
    } else if ($_GET['action'] == 'login') {
      printLoginImage();
    }
  }
?>
</body>
</html>
