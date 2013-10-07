<?php
require_once dirname(__FILE__).'/forum_sso_functions.php';

// Your code to process the logout for the user on your website goes here.

// Function call for sign out from websitetoolbox forum. This function will be called after successful user logout from your website.
// The function will print an IMG tag to get logout from websitetoolbox forum.
// Return logout out status as "Logout Successful" / "Logout Failed" from websitetoolbox forum.
$logout_status = forumSignout();
if($logout_status == 'Logout Successful') {
	// Redirect to your desired page since logout was successful.
}
?>