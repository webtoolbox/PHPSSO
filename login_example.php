<?php
require_once dirname(__FILE__).'/forum_sso_functions.php';

// YOUR CODE HERE.

// Fill in the user information in a way that websitetoolbox forum can understand.
$user['user'] = '';
// Call forum signin function to send request.
$login_status = forumSignIn($user);
echo $login_status;
?>