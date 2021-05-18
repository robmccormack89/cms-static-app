<?php
// composer autoload
require_once('vendor/autoload.php');
// app configuration
$config = require_once('config/config.php');

/**
* TESTING
**/

// print some of the data 
// print_r($GLOBALS["config"]['type_settings']['blog']['key']);
// 
// echo('<hr>');
// 
// // loop some of the data where item[key] = something
// foreach ($GLOBALS["config"]['type_settings'] as $type_setting) if ($type_setting['key'] == 'blog') {
//   print_r($type_setting['items_key']);
// }
// 
// echo('<hr>');

// function getTypeSettingBySettingKey(
//   string $key, // key of item to check against. e.g 'key' or 'items_key' or 'items_singular'
//   string $value, // value of item to check against. e.g 'blog' or 'portfolio'
//   string $return_key // key of the value to return. e.g 'items_route' 
// )
// {
//   foreach ($GLOBALS["config"]['type_settings'] as $type_setting) if ($type_setting[$key] == $value) {
//     $data = $type_setting[$return_key];
//   }
//   return $data;
// }
// 
// function getTypeSettingByAny(
//   string $type, // type of item to check against. e.g 'blog' or 'portfolio'
//   string $return_key // key of the value to return. e.g 'items_route' 
// )
// {
//   foreach ($GLOBALS["config"]['type_settings'] as $type_setting) if (in_array($type, $type_setting)) {
//     $data = $type_setting[$return_key];
//   }
//   return $data;
// }
// 
// echo(getTypeSettingBySettingKey('index_route', '/blog', 'items_route'));
// echo('<hr>');
// echo(getTypeSettingByAny('blog', 'items_route'));
// echo('<hr>');
// print_r($GLOBALS["config"]['type_settings']['blog']['index_route']);

// print_r($GLOBALS["config"]['type_settings']['blog']['taxonomies']);

// foreach ($GLOBALS['config']['types']['blog']['taxonomies'] as $taxonomy) {
//   echo($taxonomy['key']);
// }

/**
* /TESTING
**/

// load helpers
require_once('helpers/helpers.php');
require_once('helpers/json-helpers.php');
// load routes
require_once('config/routes.php');