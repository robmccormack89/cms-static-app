<?php

class Menu_model extends Core_model {
  
  public function __construct()
  {

  }
  
  public function get_menu_by_slug($menu_slug)
  {
    $the_menus = $this->get_menus();
    
    $i = array_search($menu_slug, array_column($the_menus, 'slug'));
    $element = ($i !== false ? $the_menus[$i] : null);
    
    return $element;
  }
  
}