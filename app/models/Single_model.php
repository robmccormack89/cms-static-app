<?php

class Single_model {

  // some static pages from the json file
  public function the_singles()
  {
    $str = file_get_contents('../public/json/pages.json');
    $data = json_decode($str, true); // decode the JSON into an associative array
    return $data;
  }
  
  public function get_single_by_slug($parent_slug, $child_slug) {
    
    $theSingles = $this->the_singles();
    
    if ($child_slug) {
      $slug = $parent_slug.'/'.$child_slug;
      $i = array_search($slug, array_column($theSingles, 'slug'));
      $element = ($i !== false ? $theSingles[$i] : null);
    } else {
      $i = array_search($parent_slug, array_column($theSingles, 'slug'));
      $element = ($i !== false ? $theSingles[$i] : null);
    };
    
    return $element;
    
  }
  
}