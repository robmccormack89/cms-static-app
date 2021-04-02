<?php

class Page {

  // some data
  public function the_pages()
  {
    // nested array (like an array of post objects)
    $data = array(
      array(
        "title" => "One",
        "slug" => "about",
        "director" => "Alfred Hitchcock",
        "year" => 1954
      ),
      array(
        "title" => "Two",
        "slug" => "two",
        "director" => "Stanley Kubrick",
        "year" => 1987
      ),
      array(
        "title" => "Three",
        "slug" => "three",
        "director" => "Martin Scorsese",
        "year" => 1973
      )
    );
    return $data;
  }
  
  public function get_page_by_slug($slug) {
    
    $thePages = $this->the_pages();
    $i = array_search($slug, array_column($thePages, 'slug'));
    $element = ($i !== false ? $thePages[$i] : null);
    return $element;

  }
  
}