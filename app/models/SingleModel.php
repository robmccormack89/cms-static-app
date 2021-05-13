<?php
namespace Rmcc;
use Nahid\JsonQ\Jsonq;

class SingleModel {
  
  public function __construct($name, $type, $slug, $key = null) {
    $this->name = $name;
    $this->type = $type;
    $this->slug = $slug;
    $this->key = $key;
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