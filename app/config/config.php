<?php
/*
*
* Enable debug mode. Set to false for production
*
*/
$config['enable_debug_mode'] = false;

/*
*
* Choose whether to use http or https protocol
* True results in redirects to https
*
*/
$config['enable_https'] = true;

/*
*
* Set the $root variables, with http/https, depending on whether https or not is enabled & available on the server..
* The $root variable is used in the base of global url variables
*
*/
$root = ($config['enable_https'] && isset($_SERVER['HTTPS']) ? "https://" : "http://").$_SERVER['HTTP_HOST'];

/*
*
* Global url variables; urls that are useful throughout the app
*
*/
$config['base_url'] = $root;
$config['current_url'] = $root.$_SERVER['REQUEST_URI'];
$config['current_url_clean'] = strtok($root.$_SERVER['REQUEST_URI'], "?");
$config['url_params'] = $_GET;

/*
*
* Global path variables; paths that are useful throughout the app
*
*/
$config['app_path'] = $_SERVER['DOCUMENT_ROOT'];

/*
*
* Set the $root variables, with http/https, depending on whether or not https is available on the server..
* The $root variable is used in the base of global url variables
*
*/
$config['language'] = 'en-GB';
$config['charset'] = 'UTF-8';

/*
*
* Set this for a kind of 'draft' mode on posts.
* Probably want to remove this or find another way for drafting single posts
*
*/
$config['author_ip'] = '127.0.0.1';

/*
*
* This is probaly redundant / can be set in the json data 
*
*/
$config['admin_email'] = 'info@example.com';

/*
*
* Set these to enable php caching & minification
*
*/
$config['php_cache'] = false;
$config['php_minify'] = false;

/*
*
* Set the locations that twig will look for templates in
* First set the base location (relative), then set an array with subsequent folders to look in
*
*/
$config['twig_templates_base_location'] = '../app/views/';
$config['twig_templates_locations'] = array(
  'parts',
  'archive',
  'archive/blog',
  'archive/blog/categories',
  'archive/blog/tags',
  'archive/portfolio',
  'archive/portfolio/technologies',
  'single',
  'single/page',
  'single/page/content',
  'single/post',
  'single/post/content',
  'single/post/parts',
  'single/project',
  'single/project/content',
  'single/project/parts',
);

/*
*
* Basically, this is the place to 'register' new post types
* These global settings get used in various places throughout the app, particaulary for creating urls for different archives & links etc 
* Non-archived content_type 'page' is built-in & does not need to be added here
* At the moment, all archived content_types need to be registered here
* In future, it might be better to include archived content_type's meta information here too, instead of in Json
* Also I could try to allow for new, non-archived content_types to also be registered here also, instead of just having pages built-in
* Likewise, I could try to allow for things like non-public content types, non-archived & non-singular types together etc, which would never need to be even routed
*
*/
$config['types'] = array();

// register 'blog' content_type 
$config['types']['blog'] = array(
  'key'  => 'blog',
  'items' => 'posts',
  'single' => 'post',
  'index_uri' => '/blog',
  'items_uri' => '/posts'
);

// register blog's taxonomies (categories, tags)
$config['types']['blog']['taxes_in_meta'] = array('categories', 'tags');
$config['types']['blog']['taxonomies'] = array();
$config['types']['blog']['taxonomies']['categories'] = array(
  'key'  => 'categories',
  'single'  => 'category',
  'index_uri' => '/categories',
);
$config['types']['blog']['taxonomies']['tags'] = array(
  'key'  => 'categories',
  'single'  => 'category',
  'index_uri' => '/categories',
);

// register 'portfolio' content_type 
$config['types']['portfolio'] = array(
  'key'  => 'portfolio',
  'items' => 'projects',
  'single' => 'project',
  'index_uri' => '/portfolio',
  'items_uri' => '/projects'
);

// register portfolio's taxonomies (technologies)
$config['types']['portfolio']['taxes_in_meta'] = array('technologies');
$config['types']['portfolio']['taxonomies'] = array();
$config['types']['portfolio']['taxonomies']['technologies'] = array(
  'key'  => 'technologies',
  'single'  => 'technology',
  'index_uri' => '/technologies',
);

return $config;