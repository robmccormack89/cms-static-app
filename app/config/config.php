<?php
$root = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'];

return array(
  'admin_email' => 'info@example.com', // owners email
  'charset' => 'UTF-8', // site charset
  'language' => 'en-GB', // site language
  'site_title' => 'My Website', // site title
  'site_description' => 'Nothing to see here. move along...', // site tagline
  'site_protocol' => 'http', // http or https, you decide
  'base_url' => $root, // done for ya
  'blog_url' => '/blog', // done for ya
  'portfolio_url' => '/portfolio', // done for ya
  'author_ip' => '127.0.0.1', // your ip address! if local, keep the same. If on a server, use that IP
  'visitor_ip' => $_SERVER['REMOTE_ADDR'], // the visitor's IP, done for ya
  'php_cache' => 'disable', // set 'enable' to enable php caching
  'dark_light_mode' => 'light', // default setting for dark light mode, set to 'dark' for dark mode
);
