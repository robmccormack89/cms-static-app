<?php

class Single_model {
  
  public function get_single_by_slug($parent_slug, $child_slug) {
    
    if ($child_slug) {
      $slug = $parent_slug.'/'.$child_slug;
      $i = array_search($slug, array_column($this->singles, 'slug'));
      $element = ($i !== false ? $this->singles[$i] : null);
    } else {
      $i = array_search($parent_slug, array_column($the_singles, 'slug'));
      $element = ($i !== false ? $this->singles[$i] : null);
    };
    
    return $element;
    
  }
  
}