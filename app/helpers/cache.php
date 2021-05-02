<?php
// do the cache
function cache_render($renderer) {
  echo $renderer;
  cache_file();
}
// minify helper for cache.php
function minify_output($buffer) {
  $search = array('/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s');
  $replace = array('>','<','\\1');
  if (preg_match("/\<html/i",$buffer) == 1 && preg_match("/\<\/html\>/i",$buffer) == 1) {
    $buffer = preg_replace($search, $replace, $buffer);
  }
  return $buffer;
}
// serving the cached files when they exist
function cache_serve($callback) {
    
  // part 1 - prepare requested string for use as a file name. see helpers
  $name = request_to_filename();
  
  // part 2 - if string is empty (is homepage)
  if (!$name) {
    $cachefile = $_SERVER['DOCUMENT_ROOT'].'/public/cache/php/index.html'; // filename will be index.html
  } else {
    $cachefile = $_SERVER['DOCUMENT_ROOT'].'/public/cache/php/'.$name.'.html'; // else the filename is named after part 1
  }
  
  // part 3 - minify the output, see above function
  ob_start("minify_output");
  
  // part 4 - set the expiry on the cached files
  $cachetime = 18000;
  
  // part 5 - Serve from the cache if it is younger than $cachetime
  if (file_exists($cachefile) && time() - $cachetime < filemtime($cachefile) && $GLOBALS['configs']['php_cache'] == 'enable') {
    echo "<!-- Cached copy, generated ".date('H:i', filemtime($cachefile))." -->\n";
    readfile($cachefile);
    exit;
  } else {
    call_user_func($callback);
  }
  
}
// Cache the contents to a cache file
function cache_file() {
  
  // if caching is enabled
  if($GLOBALS['configs']['php_cache'] == 'enable') {
  
    // part 1 - prepare requested string for use as a file name. see helpers
    $name = request_to_filename();
    
    // part 2 - if string is empty (is homepage)
    if (!$name) {
      $fileName = $_SERVER['DOCUMENT_ROOT'].'/public/cache/php/index.html'; // filename will be index.html
    } else {
      $fileName = $_SERVER['DOCUMENT_ROOT']."/public/cache/php/".$name.'.html'; // else the filename is named after part 1
    }
    
    // part 3 - create the cached file
    $cached = fopen($fileName, 'w');
    fwrite($cached, ob_get_contents());
    fclose($cached);
    ob_end_flush(); // Send the output to the browser
    
  }
  
}