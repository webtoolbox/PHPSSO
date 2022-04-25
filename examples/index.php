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
    <li><a href="<?php echo \WTForum\getAddress(); ?>">Forum</a></li>
		<li><a href="forum.php">Embedded forum</a></li>
    <li><a href="signup.php">Sign up</a></li>
    <li><a href="login.php">Log in</a></li>
    <li><a href="logout.php">Log out</a></li>
    <li><a href="account.php">Account</a></li>
    <li><a href="delete.php">Delete</a></li>
  </ul>
<?php
  // This is not needed if you include the authtoken in the forum link (as is done by getAddress()) and if the user is redirected to the forum log out page when they log out from your website.
  // Due to third-party cookie blocking in some browsers, the image tag approach will only work in those browsers if you're using a custom domain name (ie: https://forums.yoursite.com)
  if (isset($_GET['action'])) {
    if ($_GET['action'] == 'logout') {
      \WTForum\printLogoutImage();
    } else if ($_GET['action'] == 'login') {
      \WTForum\printLoginImage();
    }
  }
?>
</body>
</html>
