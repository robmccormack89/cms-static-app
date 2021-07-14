<?php
namespace Rmcc;

class SingleModel {
  
  public function __construct($type, $slug) {
    $this->type = $type;
    $this->slug = $slug;
    
    if(isset($GLOBALS['config']['types'][$this->type]['items'])){
      $this->key = $GLOBALS['config']['types'][$this->type]['items'];
    } else {
      $this->key = null;
    }
    $this->single = $this->getSinglular();
  }
  
  // get the singular data based on $type, $slug, $key
  private function getSinglular() {
    $q = new Json('../public/json/data.min.json');
    $data = $q->from($this->getSinglularLocation())
    ->where('slug', '=', $this->slug)
    ->first();
    
    return $data;
  }
  
  // this method sets the location of the json data query based on $type & $key
  private function getSinglularLocation() {
    if($this->type == 'page') {
      $data = 'site.content_types.'.$this->type;
    } else {
      $data = 'site.content_types.'.$this->type.'.'.$this->key;
    }
    return $data;
  }
  
}