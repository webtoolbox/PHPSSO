<?php
require_once dirname(__FILE__).'/forum_sso_functions.php';
// Important Note:
// You also need to update forum username on line 7 in forum_sso_functions.php.
// You also need to update forum API KEY on line 9 in forum_sso_functions.php.

// YOUR CODE HERE.

$user = array();
// Fill in the user information in a way that websitetoolbox forum can understand.
$user['user'] = '';

// Call forum signin function to send request.
// Get authtoken $_SESSION['authtoken'] that can be further used.
// Return login status as "Login Successful" / Logout Failed Message.
$login_status = forumSignin($user);
echo $login_status;
?>