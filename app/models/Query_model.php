<?php
// https://github.com/nahid/jsonq
use Nahid\JsonQ\Jsonq;

class Query_model extends Archive_model {
  
  // taxonomy type
  public $tax;
  // taxonomy term
  public $term;
  
  public function __construct($type, $page, $tax, $term) {
    // inherit type & page from Archive_model
    parent::__construct($type, $page);
    $this->tax = $tax;
    $this->term = $term;
  }
  
  
}