<?php

class Cache {
  
  public function __construct()
  {    
    // some data
  }
  
  // Cache the contents to a cache file
  public function cacheFile() {
    $url = $_SERVER["REQUEST_URI"];
    $string = rtrim($url, '/');
    // if string is empty (is the homepage)
    if (!$string) {
      // filename will be index.html
      $fileName = $_SERVER['DOCUMENT_ROOT'].'/public/cache/php/index.html';
    } else {
      // else the filename is named after the request
      $fileName = $_SERVER['DOCUMENT_ROOT']."/public/cache/php/".$string.'.html';
    }
    // create the cached file
    $cached = fopen($fileName, 'w');
    fwrite($cached, ob_get_contents());
    fclose($cached);
    ob_end_flush(); // Send the output to the browser
  }
  
  // serving the cached files when they exist
  public function cacheServe() {
    $url = $_SERVER["REQUEST_URI"];
    $string = rtrim($url, '/');
    $break = Explode(',', $string);
    $file = $break[count($break) - 1];
    // if file is empty (is the homepage)
    if (!$file) {
      // cached file will be named index.html
      $cachefile = $_SERVER['DOCUMENT_ROOT'].'/public/cache/php/index.html';
    } else {
      // else the file will be named after the request
      $cachefile = $_SERVER['DOCUMENT_ROOT'].'/public/cache/php/'.$file.'.html';
    }
    // set the expiry on the cached files
    $cachetime = 18000;
    // Serve from the cache if it is younger than $cachetime
    if (file_exists($cachefile) && time() - $cachetime < filemtime($cachefile)) {
        echo "<!-- Cached copy, generated ".date('H:i', filemtime($cachefile))." -->\n";
        readfile($cachefile);
        exit;
    }
    ob_start(); // Start the output buffer
  }

  
}