<?php
// https://github.com/nahid/jsonq
use Nahid\JsonQ\Jsonq;

class Menu_model {
  
  public function get_menu_by_slug($menu_slug) {
    
    $q = new Jsonq('../public/json/data.min.json');
    $menu = $q->from('site.menus')->where('slug', '=', $menu_slug)->first();

    $new['menu_items'] = menu_active_classes($menu['menu_items']);

    return $new;
  }
  
}