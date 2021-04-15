<?php
// https://github.com/nahid/jsonq
use Nahid\JsonQ\Jsonq;

class Single_model {
  
  public function get_single_by_slug($parent_slug, $child_slug) {
    $q = new Jsonq('../public/json/data.json');
    if ($child_slug) {
      $slug = $parent_slug.'/'.$child_slug;
      $page = $q->from('site.pages')->where('slug', '=', $slug)->first();
    } else {
      $page = $q->from('site.pages')->where('slug', '=', $parent_slug)->first();
    };
    return $page;
  }
  
}