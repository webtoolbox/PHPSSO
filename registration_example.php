<?php
require_once dirname(__FILE__).'/forum_sso_functions.php';
// Important Note:
// You also need to update forum username on line 5 in forum_sso_functions.php.
// You also need to update forum API Key on line 7 in forum_sso_functions.php.


// Your code to process the registration for the user on your website goes here.

// Integrating this is optional. The SSO log in call will automatically create the user's account if it doesn't already exist.

// Fill in the user information in a way that websitetoolbox forum can understand.
$user = array();

// After successful register to your website, assign the same user registration information to $user array to register at the websitetoolbox forum.
// For example: You can assign your register POST/GET values to user array like below:
// $user['member'] = $_POST['user'].

// Assign username that's displayed on the forum. 
// This can be an email address if your website doesn't have usernames. In this case, you should also pass in $user['name'] so that the user's name is shown everywhere on the forum. If no name is provided and the username is an email address, the first part of the email address (before the @) is shown everywhere on the forum.
$user['member'] = 'john';
// Assign password for new registration 
$user['pw'] = 'john123';
// Assign email id for new registration
$user['email'] = 'john.php@anonymous.com';	
// You can assign optional registration fields to the '$user' array.
// For example: $user['name'] = 'John wright';

// You can also assign optional usergroupid field if you want to register user into any specific usergroup (Uses Registered Users or Pending Approval groups by default)
// For example: $user['usergroupid'] = '489375'; 

// function called for registering a new user on websitetoolbox forum.
// Return user registeration status as "Registration Complete" / Register Failed Message.
$register_staus = forumSignup($user);
if($register_staus == 'Registration Complete') {
	// Redirect to your desired page since registeration was successful.
}
?>
