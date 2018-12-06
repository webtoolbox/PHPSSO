PHP library for Single Sign On with a Website Toolbox Forum
======

Integrate Single Sign On in PHP with a [`Website Toolbox Forum`](http://www.websitetoolbox.com/).

Instructions:

1. Provide values for the variables at the top of [`forum_sso_functions.php`](https://github.com/webtoolbox/PHPSSO/blob/master/forum_sso_functions.php)

2. Include [`forum_sso_functions.php`](https://github.com/webtoolbox/PHPSSO/blob/master/forum_sso_functions.php) in your website's PHP files wherever you need to integrate Single Sign On.

3. Copy code from the [`examples`](https://github.com/webtoolbox/PHPSSO/blob/master/examples) into your own website's files.

4. Specify your sign up, log in, and log out page URLs in the [`Single Sign On settings`](https://www.websitetoolbox.com/tool/members/mb/settings?tab=Single%20Sign%20On&highlight=website_builder).

[`Contact us`](https://www.websitetoolbox.com/contact?subject=SSO+integration+help) if you need help. Go to the [`Single Sign On settings`](https://www.websitetoolbox.com/tool/members/mb/settings?tab=Single%20Sign%20On&highlight=website_builder) and select your website builder to have our development team integrate SSO for you, starting at just $199.

You can read our [`SSO documentation`](https://www.websitetoolbox.com/support/single-sign-on-token-based-authentication-241) to understand how the approach works.

This example uses [`cURL`](http://php.net/manual/en/book.curl.php) to make HTTP requests. Most servers have cURL pre-installed. If you don't have the cURL php extension installed on your server, you can [`install it`](http://php.net/manual/en/curl.installation.php) or use a different method to make HTTP requests.  
