<?php
$root = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'];

$settings = new Site_model;

$app_settings = $settings->get_app_settings();
$site_settings = $settings->get_site_settings();
$author = $settings->get_author();

$blog_settings = $settings->get_type_settings('blog');
$portfolio_settings = $settings->get_type_settings('portfolio');

return array(
  
  // global settings
  'site_protocol' => $app_settings['site_protocol'],
  'author_ip' => $app_settings['site_ip'],
  'visitor_ip' => $_SERVER['REMOTE_ADDR'],
  'base_url' => $root,
  'current_url' => $root.$_SERVER['REQUEST_URI'],
  'admin_email' => $app_settings['site_email'],
  'php_cache' => $app_settings['site_cache'], 
  
  // site settings
  'site_title' => $site_settings['site_title'],
  'site_tagline' => $site_settings['site_tagline'],
  'site_description' => $site_settings['site_description'],
  'charset' => $site_settings['site_charset'],
  'language' => $site_settings['site_lang'],
    
  // author
  'name' => $author['name'],
  'avatar_src' => $root.$author['avatar_src'],
  'job' => $author['job'],
  'github_url' => $author['github_url'],
  
  // blog
  'is_blog_paged' => $blog_settings['is_paged'],
  'blog_posts_per_page' => $blog_settings['blog_posts_per_page'],
  'blog_route' => 'blog',
  'post_route' => 'posts',
  'category_route' => 'categories',
  'tag_route' => 'tags',
  
  // portfolio
  'is_portfolio_paged' => $portfolio_settings['is_paged'],
  'portfolio_posts_per_page' => $portfolio_settings['posts_per_page'],
  'portfolio_route' => 'portfolio',
  'project_route' => 'projects',
  
  
);