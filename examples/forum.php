<?php
require_once 'forum_sso_functions.php';
?>
<html>
<head>
	<title>Embedded Forum</title>
</head>
<body>
	<h2>Embedded Forum</h2>
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
	<p>This is an example of an embedded forum with SSO.</p>

	<!--
	This code is from the General -> Embed code section of the Website Toolbox admin area.
	It uses the domain of the forum and passes the authtoken as a parameter.
 	-->
	<!--Begin Website Toolbox Forum Embed Code-->
	<div id="wtEmbedCode"><script type="text/javascript" id="embedded_forum" src="<?php echo \WTForum\getDomain(); ?>/js/mb/embed.js?<?php echo \WTForum\getAuthParams(); ?>"></script>
	<noscript><a href="<?php echo \WTForum\getDomain(); ?>">Forum</a></noscript></div>
	<!--End Website Toolbox Forum Embed Code-->
</body>
</html>
