<?php

class Single_controller extends Core_controller {
  
  public $single;
  
  public function __construct()
  {
    parent::__construct();
    $this->single = new Single_model;
  }

  public function any($parent_slug, $child_slug) {
    
    $context['single'] = $this->single->get_single_by_slug($parent_slug, $child_slug);
    
    if ($context['single']) {
      // render the view template with the context
      $this->render_single($context['single'], $context);
    } else {
      $this->error();
    }
    
  }
  
}