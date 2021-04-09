<?php

class Single_model {

  // some static pages from the json file
  public function get_singles()
  {
    $str = file_get_contents('../public/json/singles.json');
    $data = json_decode($str, true); // decode the JSON into an associative array
    return $data;
  }
  
  public function get_single_by_slug($parent_slug, $child_slug) {
    
    $the_singles = $this->get_singles();
    
    if ($child_slug) {
      $slug = $parent_slug.'/'.$child_slug;
      $i = array_search($slug, array_column($the_singles, 'slug'));
      $element = ($i !== false ? $the_singles[$i] : null);
    } else {
      $i = array_search($parent_slug, array_column($the_singles, 'slug'));
      $element = ($i !== false ? $the_singles[$i] : null);
    };
    
    return $element;
    
  }
  
}