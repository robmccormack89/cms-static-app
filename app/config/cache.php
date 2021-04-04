<?php

class Cache {
  
  public function __construct()
  {    
  
  }
  
  public function cacheFile() {
    // Cache the contents to a cache file
    $url = $_SERVER["REQUEST_URI"];
    $string = rtrim($url, '/');
    $fileName = $_SERVER['DOCUMENT_ROOT']."/public/cache/php/".$string.'.html';
    $cached = fopen($fileName, 'w');
    fwrite($cached, ob_get_contents());
    fclose($cached);
    ob_end_flush(); // Send the output to the browser
  }
  
  public function cacheServe() {
    $url = $_SERVER["REQUEST_URI"];
    $string = rtrim($url, '/');
    $break = Explode(',', $string);
    $file = $break[count($break) - 1];
    // $cachefile = 'cached-'.$file.'.html';
    $cachefile = $_SERVER['DOCUMENT_ROOT'].'/public/cache/php/'.$file.'.html';
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