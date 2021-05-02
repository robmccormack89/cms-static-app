<?php

class Cache {
  
  public function __construct()
  {
  }
  
  // serving the cached files when they exist
  public function cacheServe() {
    $req = $_SERVER["REQUEST_URI"];
    // strip character/s from end of string
    $string = rtrim($req, '/');
    // strip character/s from beginning of string
    $trimmed = ltrim($string, '/');
    $name = str_replace('/', '-', $trimmed);
    
    // if file is empty (is the homepage)
    if (!$name) {
      // cached file will be named index.html
      $cachefile = $_SERVER['DOCUMENT_ROOT'].'/public/cache/php/index.html';
    } else {
      // else the file will be named after the request
      $cachefile = $_SERVER['DOCUMENT_ROOT'].'/public/cache/php/'.$name.'.html';
    }
    
    ob_start("minify_output"); // Start the output buffer
    
    // set the expiry on the cached files
    $cachetime = 18000;
    // Serve from the cache if it is younger than $cachetime
    if (file_exists($cachefile) && time() - $cachetime < filemtime($cachefile)) {
        echo "<!-- Cached copy, generated ".date('H:i', filemtime($cachefile))." -->\n";
        readfile($cachefile);
        exit;
    }
  }

  // Cache the contents to a cache file
  public function cacheFile() {
    $req = $_SERVER["REQUEST_URI"];
    // strip character/s from end of string
    $string = rtrim($req, '/');
    // strip character/s from beginning of string
    $trimmed = ltrim($string, '/');
    $name = str_replace('/', '-', $trimmed);
    // print_r($filename);
    
    // if string is empty (is the homepage)
    if (!$name) {
      // filename will be index.html
      $fileName = $_SERVER['DOCUMENT_ROOT'].'/public/cache/php/index.html';
    } else {
      // else the filename is named after the request
      $fileName = $_SERVER['DOCUMENT_ROOT']."/public/cache/php/".$name.'.html';
    }
    // create the cached file
    $cached = fopen($fileName, 'w');
    fwrite($cached, ob_get_contents());
    fclose($cached);
    ob_end_flush(); // Send the output to the browser
  }

  
}