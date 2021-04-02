<?php

class Site {
  
  public function __construct()
  {
  }

  // simple array
  public function get_site()
  {
    $data = array(
      "title" => "Static.com",
      "tagline" => "Json-based Posts/Portfolio CMS",
      "baseurl" => "http://static.com",
    );
    return $data;
  }
  
}