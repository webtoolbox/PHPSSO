PHPSSO
======

Contains client files for integrating Single Sign On and Single Registration in PHP with a [`Website Toolbox Forum`](http://www.websitetoolbox.com/forum-hosting/index.html).

The following files are included in this repo.

* [`forum_sso_functions.php`](https://github.com/webtoolbox/PHPSSO/blob/master/forum_sso_functions.php)
  This is the main file you need. You don't need any other file in your project. You just need to give your forum address and forum API Key at the top of the script. Then you can drop this file anywhere that you can access it on your site. 
* [`registration_example.php`](https://github.com/webtoolbox/PHPSSO/blob/master/registration_example.php)
  This file offers an example usage for single registration. You can customize this page or start from scratch.
* [`login_example.php`](https://github.com/webtoolbox/PHPSSO/blob/master/login_example.php)
  This file offers an example usage for SSO login. You can customize this page or start from scratch.
* [`logout_example.php`](https://github.com/webtoolbox/PHPSSO/blob/master/logout_example.php)
  This file offers an example usage for SSO logout. You can customize this page or start from scratch.
* This example uses <a href="http://php.net/manual/en/book.curl.php</a>cURL</a> to make HTTP requests. Most servers have cURL pre-installed. If you don't have the cURL php extension installed on your server, you can <a href="http://php.net/manual/en/curl.installation.php>install it</a> or use a different method to make HTTP requests.   
  
Note that PHP's cURL extension is required. That is installed on most servers by default.
  
  
 
