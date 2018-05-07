<?php
require_once dirname(__FILE__).'/forum_sso_functions.php';
// Important Note:
// You also need to update forum username on line 5 in forum_sso_functions.php.
// You also need to update forum API Key on line 7 in forum_sso_functions.php.

// Your code to process the login for the user on your website goes here.

// Fill in the user information in a way that websitetoolbox forum can understand.
$user = array();
// After successful login to your website, assign the same logged-in username which also stored in the websitetoolbox forum.
// You can also login using an email address. For example: $user['user'] = 'john@gmail.com';
$user['user'] = 'john';

// You can also pass the user's email address (optional). It will automatically create the forum account in cases where the specified forum account doesn't already exist.
$user['email'] = 'john@gmail.com';

// You can also pass the plain-text password (Optional) to set the account's password in cases where a new account needs to be created because it doesn't already exist.
// If you choose not to pass the plain-text password with your request and a new account needs to be created, it would be created without a password.
// In that case the user would not be able to login directly into the forum unless they first reset their password on the login page. 
// SSO login would work smoothly even without the user's account having a password.
$user['pw'] = 'john123';

// This is optional. It is useful if an email address is being used as the user's username and the forum is creating a new user account because it doesn't already exist.
$user['name'] = 'John Smith';

// function called for sign in on the websitetoolbox forum if username exist on user's site as well as on Website Toolbox forum.
// The function will print an IMG tag to get login on websitetoolbox forum.
// You can also get authtoken $_SESSION['authtoken'] that can be further used for hiding "login" page after successful login and displaying "logout" page on your website.
// The function will return login status as "Login Successful" / Logout Failed Message from websitetoolbox forum.
$login_status = forumSignin($user);
if($login_status == 'Login Successful') {
	// Redirect to secure members-only area since login was successful.
}
?>
