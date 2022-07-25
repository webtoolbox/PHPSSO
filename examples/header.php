<ul>
    <li><a href="index.php">Home</a></li>
    <li><a href="<?php echo \WTForum\getAddress(); ?>">Forum</a></li>
    <li><a href="forum.php">Embedded forum</a></li>
    <?php if (isset($_COOKIE['forum_userid'])) { ?>
        <li><a href="logout.php">Log out</a></li>
        <li><a href="settings.php">Account</a></li>
        <li><a href="delete.php">Delete</a></li>
    <?php } else { ?>
        <li><a href="signup.php">Sign up</a></li>
        <li><a href="login.php">Log in</a></li>
    <?php } ?>
</ul>
