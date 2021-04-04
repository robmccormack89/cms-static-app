<?php

class Movies {

  // some data
  public function get_movies()
  {
    $str = file_get_contents('../public/json/movies.json');
    $data = json_decode($str, true); // decode the JSON into an associative array
    return $data;
  }
  
}