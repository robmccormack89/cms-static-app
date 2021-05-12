<?php
use Nahid\JsonQ\Jsonq;

class Single_model {
  
  public $type;
  public $slug;
  
  public function __construct($type, $slug) {
    $this->type = $type;
    $this->slug = $slug;
  }
  
  public function get_single() {
    $q = new Jsonq('../public/json/data.min.json');
    $data = $q->from(get_singles_location($this->type))->where('slug', '=', $this->slug)->first();
    return $data;
  }
  
}