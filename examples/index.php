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
  <?php include('header.php'); ?>
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
