<?php
$site_settings = new Settings_model;
$settings = $site_settings->get_settings();
$root = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'];
$blog_url = '/' . $site_settings->get_blog_url();
$portfolio_url = '/' . $site_settings->get_portfolio_url();

return array(
  'admin_email' => $settings['site_email'], // owners email**
  'charset' => $settings['site_charset'], // site charset**
  'language' => $settings['site_lang'], // site language**
  'site_title' => $settings['site_title'], // site title**
  'site_description' => $settings['site_description'], // site tagline**
  'site_tagline' => $settings['site_tagline'], // site tagline**
  'site_protocol' => $settings['site_protocol'], // http or https, you decide**
  'base_url' => $root, // done for ya
  'blog_url' => $blog_url, // done for ya
  'portfolio_url' => $portfolio_url, // done for ya
  'author_ip' => $settings['site_ip'], // your ip address! if local, keep the same. If on a server, use that IP**
  'visitor_ip' => $_SERVER['REMOTE_ADDR'], // the visitor's IP, done for ya
  'php_cache' => $settings['site_cache'], // set 'enable' to enable php caching
  'dark_light_mode' => $settings['dark_light_mode'], // default setting for dark light mode, set to 'dark' for dark mode
  'current_url' => $root.$_SERVER['REQUEST_URI'],
  'placeholder_img_src' => $settings['placeholder_img']
);