<?php
namespace Rmcc;
use Nahid\JsonQ\Jsonq;

class SingleModel {
  
  public function __construct($name, $type, $slug, $key = null) {
    $this->name = $name; // string. twig template name. e.g 'page', 'post' or 'project'
    $this->type = $type; // string. e.g 'page' or 'blog'. if set to 'page', $key is unnecessary
    $this->slug = $slug; // string. requested page slug. passed on via routes
    $this->key = $key; // string. e.g 'posts' or 'projects'. the plural items key for the archived content type.
  }
  
  // get the singular data based on $type, $slug, $key
  public function getSinglular() {
    $q = new Jsonq('../public/json/data.min.json');
    $data = $q->from($this->getSinglularLocation())
    ->where('slug', '=', $this->slug)
    ->first();
    
    return $data;
  }
  
  // this method sets the location of the json data query based on $type & $key
  protected function getSinglularLocation() {
    if($this->key == null) {
      $data = 'site.content_types.'.$this->type;
    } else {
      $data = 'site.content_types.'.$this->type.'.'.$this->key;
    }
    return $data;
  }
  
}