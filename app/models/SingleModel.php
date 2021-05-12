<?php
namespace Rmcc;
use Nahid\JsonQ\Jsonq;

class SingleModel {
  
  public $type; // string. e.g 'page' or 'blog'. if set to 'page', $key is unnecessary
  public $slug; // string. requested page slug. passed on via routes
  public $key; // string. e.g 'posts' or 'projects'. set this for nested content types (types with archive indexes) to point to actual posts
  
  public function __construct($type, $slug, $key = null) {
    $this->type = $type;
    $this->slug = $slug;
    $this->key = $key;
  }
  
  public function getSingle() {
    $q = new Jsonq('../public/json/data.min.json');
    $data = $q->from($this->getSinglesLocation())
    ->where('slug', '=', $this->slug)
    ->first();
    
    return $data;
  }
  
  protected function getSinglesLocation() {
    if($this->key == null) {
      $data = 'site.content_types.'.$this->type;
    } else {
      $data = 'site.content_types.'.$this->type.'.posts';
    }
    return $data;
  }
  
}