<?php

class Site {
  
  public function __construct()
  {
  }

  // simple array
  public function get_site()
  {
    $str = file_get_contents('../public/json/site.json');
    $data = json_decode($str, true); // decode the JSON into an associative array
    return $data;
  }
  
}