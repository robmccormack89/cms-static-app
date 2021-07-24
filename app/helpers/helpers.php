<?php
// string $key, // key of item to check against. e.g 'key' or 'items_key' or 'items_singular'
// string $value, // value of item to check against. e.g 'blog' or 'portfolio'
// string $return_key // key of the value to return. e.g 'items_route' 
function getTypeSettingBySettingKey(string $key, string $value, string $return_key) {
  foreach ($GLOBALS["config"]['types'] as $type_setting) if ($type_setting[$key] == $value) {
    $data = $type_setting[$return_key];
  }
  return $data;
}
// get taxonomy setting. same as above just added type paramter to get a types taxonomy
function getTypeTaxSettingBySettingKey(string $type, string $key, string $value, string $return) {
  foreach ($GLOBALS["config"]['types'][$type]['taxonomies'] as $tax_setting) if ($tax_setting[$key] == $value) {
    $data = $tax_setting[$return];
  }
  return $data;
}

function type_setting_by_key(string $key, string $value, string $return_key) {
  foreach ($GLOBALS["config"]['types'] as $type_setting) if ($type_setting[$key] == $value) {
    $data = $type_setting[$return_key];
  }
  return $data;
}
// get taxonomy setting. same as above just added type paramter to get a types taxonomy
function tax_setting_by_key(string $type, string $key, string $value, string $return) {
  foreach ($GLOBALS["config"]['types'][$type]['taxonomies'] as $tax_setting) if ($tax_setting[$key] == $value) {
    $data = $tax_setting[$return];
  }
  return $data;
}

// check to see if singular page is status, visitor ip & author_ip
function isSingleAllowed($page) {
  if($page) {
    if ($page['status'] == 'draft' && $_SERVER['REMOTE_ADDR'] == $GLOBALS['config']['author_ip']) {
      return true;
    } elseif($page['status'] == 'published') {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
}
// remove & replace the slashes in the requested string with hyphens for use as a file name. from given slug
function slugToFilename($slug) {
  // strip character/s from end of string
  $string = rtrim($slug, '/');
  // strip character/s from beginning of string
  $trimmed = ltrim($string, '/');
  
  $data = str_replace('/', '-', $trimmed);
  
  return $data;
}
// get objects in array using key->value
function getInArray( string $needle, array $haystack, string $column){
  $matches = [];
  foreach( $haystack as $item )  if( $item[ $column ] === $needle )  $matches[] = $item;
  return $matches;
}
// minify html, for use with ob_start
function minifyOutput($buffer) {
  $search = array('/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s');
  $replace = array('>','<','\\1');
  if (preg_match("/\<html/i",$buffer) == 1 && preg_match("/\<\/html\>/i",$buffer) == 1) {
    $buffer = preg_replace($search, $replace, $buffer);
  }
  return $buffer;
}