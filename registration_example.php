<?php
require_once dirname(__FILE__).'/forum_sso_functions.php';

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
$response = forumSignup($user);
// Response has user's registration success status
echo $response;
?>