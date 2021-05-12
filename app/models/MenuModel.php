<?php
namespace Rmcc;
use Nahid\JsonQ\Jsonq;

// creation of Menu object requires $slug. E.g new MenuModel('main-menu')
// Menu object is returned with 3 properties: $slug, $title, $menu_items
class MenuModel {
  
  public $slug;
  public $title;
  public $menu_items;
  
  public function __construct($slug) {
    $this->slug = $slug;
    $this->title = $this->getMenu()['title'];
    $this->menu_items = $this->getMenu()['menu_items'];
  }
  
  // get a menu via its slug
  protected function getMenu() {
    $q = new Jsonq('../public/json/data.min.json');
    $data = $q->from('site.menus')->where('slug', '=', $this->slug)->first();
    $data['menu_items'] = self::setMenuItemsClasses($data['menu_items']);

    return $data;
  }
  
  // traverse a given set of menu items, and add active classes if link is found in REQUEST_URI
  protected static function setMenuItemsClasses($menu_items) {
    foreach ($menu_items as $k => &$item) {
      if ($_SERVER['REQUEST_URI'] == $menu_items[$k]['link']) {
        $menu_items[$k]['class'] = 'uk-active';
      }
      if(isset($menu_items[$k]['children'])){
        foreach ($menu_items[$k]['children'] as $key => &$value) {
          if ($_SERVER['REQUEST_URI'] == $value['link']) {
            $value['class'] = 'uk-active';
          }
        }
      }
    }
    return($menu_items);
  }
  
}