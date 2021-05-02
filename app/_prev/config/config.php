<?php
$root = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'];

$settings = new Data_model;

$app_settings = $settings->get_app_settings();
$site_settings = $settings->get_site_settings();
$author = $settings->get_author();

$blog_settings = $settings->get_type_settings('blog');
$portfolio_settings = $settings->get_type_settings('portfolio');

return array(
  
  // global settings
  'site_protocol' => $settings['site_protocol'],
  'author_ip' => $settings['site_ip'],
  'visitor_ip' => $_SERVER['REMOTE_ADDR'],
  'base_url' => $root,
  'current_url' => $root.$_SERVER['REQUEST_URI'],
  'admin_email' => $settings['site_email'],
  'php_cache' => $settings['site_cache'], 
  
  // site settings
  'site_title' => $settings['site_title'],
  'site_tagline' => $settings['site_tagline'],
  'site_description' => $settings['site_description'],
  'charset' => $settings['site_charset'],
  'language' => $settings['site_lang'],
    
  // author
  'name' => $settings['name'],
  'avatar_src' => $root.$settings['avatar_src'],
  'job' => $settings['job'],
  'github_url' => $settings['github_url'],
  
  // (cpts) (blog)
  'blog_url' => $blog_settings['archive_url'],
  'post_url' => $blog_settings['single_url'],
  'category_url' => $blog_settings['category_url'],
  'tag_url' => $blog_settings['tag_url'],
  'is_blog_paged' => $blog_settings['is_paged'],
  'blog_posts_per_page' => $blog_settings['blog_posts_per_page'],
  
  // (cpts) (portfolio)
  'portfolio_url' => $portfolio_settings['archive_url'],
  'project_url' => $portfolio_settings['single_url'],
  'is_portfolio_paged' => $portfolio_pagi['is_paged'],
  'portfolio_posts_per_page' => $blog_settings['posts_per_page'],
  
);