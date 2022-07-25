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
  <?php include('header.php'); ?>
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
