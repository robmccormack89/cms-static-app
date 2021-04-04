<?php

class Page {

  // some data
  public function the_pages()
  {
    $str = file_get_contents('../public/json/pages.json');
    $data = json_decode($str, true); // decode the JSON into an associative array
    return $data;
  }
  
  public function get_page_by_slug($slug) {
    
    $thePages = $this->the_pages();
    $i = array_search($slug, array_column($thePages, 'slug'));
    $element = ($i !== false ? $thePages[$i] : null);
    return $element;

  }
  
}