<?php
namespace Rmcc;

class SingleModel {
  
  /*
  *
  * This class is used to create singular objects like pages, posts, projects etc.
  * Create a new SingleModel object with $type & $slug properties.
  * The $single property of the created object contains all the data for the singular item
  *
  */
  public function __construct(string $type, string $slug) {
    $this->type = $type; // e.g 'page' or 'blog' or 'portfolio'
    $this->slug = $slug; // e.g 'about'. this will usually come from the request unless setting for specific pages
    
    // the $key property is used for locating the singular data based on the given $type
    if(isset($GLOBALS['config']['types'][$this->type]['items'])){
      $this->key = $GLOBALS['config']['types'][$this->type]['items'];
    } else {
      $this->key = null;
    }
    
    $this->single = $this->getSinglular(); // this property then contains all our data
  }
  
  // get the singular object
  private function getSinglular() {
    $q = new Json('../public/json/data.min.json');
    $data = $q->from($this->getSinglularLocation())
    ->where('slug', '=', $this->slug)
    ->first();
    return $data;
  }
  
  // this method sets the location of the data based on $type & $key.
  // this is used mainly to differenciate between archived & non-archived singular objects when getting data
  private function getSinglularLocation() {
    if($this->type == 'page') {
      $data = 'site.content_types.'.$this->type;
    } else {
      $data = 'site.content_types.'.$this->type.'.'.$this->key;
    }
    return $data;
  }
  
}