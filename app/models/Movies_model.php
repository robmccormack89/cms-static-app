<?php

class Movies {

  // some data
  public function get_movies()
  {
    // nested array (like an array of post objects)
    $data = array(
      array(
        "title" => "Rear Window",
        "director" => "Alfred Hitchcock",
        "year" => 1954
      ),
      array(
        "title" => "Full Metal Jacket",
        "director" => "Stanley Kubrick",
        "year" => 1987
      ),
      array(
        "title" => "Mean Streets",
        "director" => "Martin Scorsese",
        "year" => 1973
      )
    );
    return $data;
  }
  
}