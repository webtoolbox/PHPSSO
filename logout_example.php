<?php
require_once dirname(__FILE__).'/forum_sso_functions.php';

// Call ForumSignout function to SSO sign out.
// Return logout out status as "Logout Successful" / "Logout Failed"
$logout_status = forumSignout();
echo $logout_status;
?>