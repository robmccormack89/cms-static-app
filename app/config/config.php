<?php
$site_settings = new Settings_model;
$settings = $site_settings->get_settings();
$root = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'];
// (cpts)
$blog_settings = $site_settings->get_blog_settings();
$blog_pagi = $site_settings->get_blog_meta()->pagination;
$portfolio_settings = $site_settings->get_portfolio_settings();
$portfolio_pagi = $site_settings->get_portfolio_meta()->pagination;

return array(
  'admin_email' => $settings['site_email'], // owners email**
  'charset' => $settings['site_charset'], // site charset**
  'language' => $settings['site_lang'], // site language**
  'site_title' => $settings['site_title'], // site title**
  'site_description' => $settings['site_description'], // site tagline**
  'site_tagline' => $settings['site_tagline'], // site tagline**
  'site_protocol' => $settings['site_protocol'], // http or https, you decide**
  'base_url' => $root, // done for ya
  'current_url' => $root.$_SERVER['REQUEST_URI'],
  'author_ip' => $settings['site_ip'], // your ip address! if local, keep the same. If on a server, use that IP**
  'visitor_ip' => $_SERVER['REMOTE_ADDR'], // the visitor's IP, done for ya
  'php_cache' => $settings['site_cache'], // set 'enable' to enable php caching
  'dark_light_mode' => $settings['dark_light_mode'], // default setting for dark light mode, set to 'dark' for dark mode
  'placeholder_img_src' => $settings['placeholder_img'],
  
  'name' => $settings['name'],
  'avatar_src' => $root.$settings['avatar_src'],
  'job' => $settings['job'],
  'github_url' => $settings['github_url'],
  
  // (cpts) (blog)
  'blog_url' => $blog_settings['archive_url'], // done for ya
  'post_url' => $blog_settings['single_url'], // done for ya
  'is_blog_paged' => $blog_pagi['is_paged'],
  // (cpts) (portfolio)
  'portfolio_url' => $portfolio_settings['archive_url'], // done for ya
  'project_url' => $portfolio_settings['single_url'], // done for ya
  'is_portfolio_paged' => $portfolio_pagi['is_paged'],
  // (cpts) (blog cats)
  'category_url' => $blog_settings['category_url'], // done for ya
  'tag_url' => $blog_settings['tag_url'], // done for ya
);