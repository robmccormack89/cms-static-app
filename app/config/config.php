<?php
$root = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'];

// configs
$config['base_url'] = $root;
$config['language'] = 'en-GB';
$config['charset'] = 'UTF-8';
$config['author_ip'] = '127.0.0.1';
$config['site_protocol'] = 'http';
$config['current_url'] = $root.$_SERVER['REQUEST_URI'];
$config['admin_email'] = 'info@example.com';
$config['php_cache'] = false;
$config['php_minify'] = false;

// configure this for archived content types. pages is built in & is a singular-type only
$config['types'] = array(
  
  'blog'  => array(
    'key'  => 'blog',
    'items' => 'posts',
    'single' => 'post',
    'index_uri' => '/blog',
    'items_uri' => '/posts',
    'taxes_in_meta' => array('categories', 'tags'),
    'taxonomies' => array(
      'categories' => array(
        'key'  => 'categories',
        'index_uri' => '/categories',
      ), 
      'tags' => array(
        'key'  => 'tags',
        'index_uri' => '/tags',
      )
    ),
  ),
  
  'portfolio' => array(
    'key'  => 'portfolio',
    'items' => 'projects',
    'single' => 'project',
    'index_uri' => '/portfolio',
    'items_uri' => '/portfolio/projects'
  ),
  
);

return $config;