<?php
require_once dirname(__FILE__).'/forum_sso_functions.php';
// Important Note:
// You also need to update forum username on line 7 in forum_sso_functions.php.
// You also need to update forum API KEY on line 9 in forum_sso_functions.php.

// YOUR CODE HERE.

// Fill in the user information in a way that websitetoolbox forum can understand.
$user = array();
// Assign username that's displayed on the forum
$user['member'] = 'john';
// Assign password for new registration 
$user['pw'] = 'john123';
// Assign email id for new registration
$user['email'] = 'john.php@anonymous.com';	
// You can also assign optional registration fields to '$user' array.
// For example: $user['name'] = 'John wright';

// Call forum signup function to send request.
// Return user registeration status as "Registration Complete" / Register Failed Message.
$register_staus = forumSignup($user);
echo $register_staus;
?>