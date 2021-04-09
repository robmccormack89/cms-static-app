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
  
  // custom templates according to type
  public function render_single($obj, $context) {
    if (is_page_post_or_project($obj)) {
      $this->render_template($obj['type'].'-'.$obj['slug'].'.twig', $obj['type'].'.twig', 'single.twig', $context);
    } else {
      $this->render_template('single-'.$obj['slug'].'.twig', 'single.twig', '404.twig', $context);
    };
  }
  
}