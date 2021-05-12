<?php
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
// remove & replace the slashes in the requested string with hyphens for use as a file name. from request
function requestToFilename() {
  $data = slugToFilename($_SERVER["REQUEST_URI"]);
  
  return $data;
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