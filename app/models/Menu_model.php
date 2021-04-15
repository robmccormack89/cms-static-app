<?php
// https://github.com/nahid/jsonq
use Nahid\JsonQ\Jsonq;

class Menu_model {
  
  public function get_menu_by_slug($menu_slug) {
    
    $q = new Jsonq('../public/json/data.json');
    $menu = $q->from('site.menus')->where('slug', '=', $menu_slug)->first();

    return $menu;
  }
  
}