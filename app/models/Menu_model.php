<?php

class Menu_model {
  
  public function get_menu_by_slug($menu_slug)
  {
    
    $i = array_search($menu_slug, array_column(get_json_data('menus'), 'slug'));
    $element = ($i !== false ? get_json_data('menus')[$i] : null);
    
    return $element;
  }
  
}