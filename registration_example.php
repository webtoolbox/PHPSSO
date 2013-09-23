<?php
require_once dirname(__FILE__).'/forum_sso_functions.php';
// Important Note:
// You also need to update forum username on line 7 in forum_sso_functions.php.
// You also need to update forum API KEY on line 9 in forum_sso_functions.php.

// YOUR CODE HERE.

// Fill in the user information in a way that websitetoolbox forum can understand.
$user = array();
// Assign username that's displayed on the forum
$user['member'] = '';
// Assign password for new registration 
$user['pw'] = '';
// Assign email id for new registration
$user['email'] = 'john.php@anonymous.com';	

// Call forum signup function to send request.
// Return user registeration status as "Registration Complete" / Register Failed Message.
$register_staus = forumSignup($user);
echo $register_staus;
?>