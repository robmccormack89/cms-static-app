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
  public function __construct() {
    $this->type = $GLOBALS['_context']['type']; // e.g 'page' or 'blog' or 'portfolio'
    $this->slug = $GLOBALS['_context']['slug']; // e.g 'about'. this will usually come from the request unless setting for specific pages
    // the $key property is used for locating the singular data based on the given $type
    $this->key = (isset($GLOBALS['config']['types'][$this->type]['items'])) ? $GLOBALS['config']['types'][$this->type]['items'] : null;
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
    $data = ($this->type == 'page') ? 'site.content_types.'.$this->type : 'site.content_types.'.$this->type.'.'.$this->key;
    return $data;
  }
  
}