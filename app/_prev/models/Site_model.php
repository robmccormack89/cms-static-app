<?php
use Nahid\JsonQ\Jsonq;

class Site_model {
  
  // stuff thats related to functionality & not supposed to be available on frontend
  public function get_app_settings() {
    $q = new Jsonq('../public/json/data.min.json');
    $data = $q->from('settings')->get();

    return $data;
  }
  
  // global site settings: site_title, site_tagline, site_description, site_charset, site_lang
  public function get_site_settings() {
    $q = new Jsonq('../public/json/data.min.json');
    $data = $q->from('site.settings')->get();

    return $data;
  }
  
  // get the author data
  public function get_author() {
    $q = new Jsonq('../public/json/data.min.json');
    $data = $q->from('site.author')->get();

    return $data;
  }
  
  // get post type settings (blog or portfolio)
  public function get_type_meta($type) {
    $q = new Jsonq('../public/json/data.min.json');
    $data = $q->from('site.'.$type.'.meta')->get();

    return $data;
  }
  public function get_type_settings($type) {
    $q = new Jsonq('../public/json/data.min.json');
    $data = $q->from('site.'.$type.'.settings')->get();

    return $data;
  }
  public function get_type_singular_url($type) {
    $data = $this->get_type_settings($type);

    return $data['single_url'];
  }
  public function get_type_archive_url($type) {
    $data = $this->get_type_settings($type);

    return $data['archive_url'];
  }
  public function get_type_taxonomies($type) {
    $q = new Jsonq('../public/json/data.min.json');
    $data = $q->from('site.'.$type.'.taxonomies')->get();

    return $data;
  }
  public function get_tax_terms($type, $tax) {
    $q = new Jsonq('../public/json/data.min.json');
    $data = $q->from('site.'.$type.'.taxonomies.'.$tax.'')->get();

    return $data;
  }
  public function get_term_title_from_slug($type, $tax, $slug) {
    $q = new Jsonq('../public/json/data.min.json');
    $term = $q->from('site.'.$type.'.taxonomies.'.$tax)
    ->where('slug', '=', $slug)
    ->first();
    
    return $term->title;
  }
  
  // get a menu by its slug
  public function get_menu($slug) {
    $q = new Jsonq('../public/json/data.min.json');
    $menu = $q->from('site.menus')->where('slug', '=', $slug)->first();
    $data['menu_items'] = menu_active_classes($menu['menu_items']);

    return $data;
  }

}