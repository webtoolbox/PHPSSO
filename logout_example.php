<?php
require_once dirname(__FILE__).'/forum_sso_functions.php';
// Important Note:
// You also need to update forum username on line 6 in forum_sso_functions.php.
// You also need to update forum API KEY on line 8 in forum_sso_functions.php.

// Call ForumSignout function to SSO sign out.
// Return logout out status as "Logout Successful" / "Logout Failed"
$logout_status = forumSignout();
echo $logout_status;
?>