<html>
<head>
	<title>Log out successful!</title>
</head>
<body>
	<h2>Log out successful!</h2>
	<!-- 
	This could be your home page or whatever page you redirect the user to after they successfully log out 
	of your website. You'll call the forumSignout() function on that page and it'll print an HTML IMG tag that 
	will log the user out.
	-->
<?php
	require_once 'forum_sso_functions.php';

	// print an IMG tag on the page to log the user out
	$logoutResponse = forumSignout();
	if($logoutResponse != 'Logout Successful') {
		error_log("Logging out from Website Toolbox forum failed: " . $logoutResponse);
	}
?>

</body>
</html>
