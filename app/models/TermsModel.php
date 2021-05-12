<?php
namespace Rmcc;
use Nahid\JsonQ\Jsonq;

public function __construct() {
}

class TermsModel {
  
  public function get_tease_url($type, $item) {
    $data = get_type_post_route($type).'/'.$item['slug'];
    
    return $data;
  }
  public function get_tease_term_data($terms, $tax) {
    foreach ($terms as $term) {
      $term['link'] = get_tease_term_url($tax, $term);
    
      $data[] = $term;
    }
    return $data;
  }
  public function get_tease_term_url($tax, $item) {
    $data = get_tax_post_route($tax).'/'.$item['slug'];
    
    return $data;
  }
  
}