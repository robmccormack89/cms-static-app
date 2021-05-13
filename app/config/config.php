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

return $config;