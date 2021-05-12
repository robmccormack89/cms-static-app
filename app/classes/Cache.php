<?php
namespace Rmcc;

class Cache {
  
  // do the cache
  public static function cacheRender($renderer) {
    echo $renderer;
    self::cacheFile();
  }
  // Cache the contents to a cache file
  public static function cacheFile() {
    
    // if caching is enabled
    if($GLOBALS['config']['php_cache']) {
    
      // part 1 - prepare requested string for use as a file name. see helpers
      $name = requestToFilename();
      
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
  // serving the cached files when they exist
  public static function cacheServe($callback) {
      
    // part 1 - prepare requested string for use as a file name. see helpers
    $name = requestToFilename();
    
    // part 2 - if string is empty (is homepage)
    if (!$name) {
      $cachefile = $_SERVER['DOCUMENT_ROOT'].'/public/cache/php/index.html'; // filename will be index.html
    } else {
      $cachefile = $_SERVER['DOCUMENT_ROOT'].'/public/cache/php/'.$name.'.html'; // else the filename is named after part 1
    }
    
    // part 3 - minify the output, see above function
    if($GLOBALS['config']['php_minify']) {
      ob_start('minifyOutput');
    }
    
    // part 4 - set the expiry on the cached files
    $cachetime = 18000;
    
    // part 5 - Serve from the cache if it is younger than $cachetime
    if (file_exists($cachefile) && time() - $cachetime < filemtime($cachefile) && $GLOBALS['config']['php_cache']) {
      echo "<!-- Cached copy, generated ".date('H:i', filemtime($cachefile))." -->\n";
      readfile($cachefile);
      exit;
    } else {
      call_user_func($callback);
    }
    
  }
  
}