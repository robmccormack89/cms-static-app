<?php
$root = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'];

// configs
$config['base_url'] = $root;
$config['language'] = 'en-GB';
$config['charset'] = 'UTF-8';
$config['author_ip'] = '127.0.0.1';
$config['site_protocol'] = 'https';
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
        'single'  => 'category',
        'index_uri' => '/categories',
      ), 
      'tags' => array(
        'key'  => 'tags',
        'single'  => 'tag',
        'index_uri' => '/tags',
      )
    ),
  ),
  
  'portfolio' => array(
    'key'  => 'portfolio',
    'items' => 'projects',
    'single' => 'project',
    'index_uri' => '/portfolio',
    'items_uri' => '/projects',
    'taxes_in_meta' => array('technologies'),
    'taxonomies' => array(
      'technologies' => array(
        'key'  => 'technologies',
        'single'  => 'techonology',
        'index_uri' => '/technologies',
      )
    ),
  ),
  
);

return $config;