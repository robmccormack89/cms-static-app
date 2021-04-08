<?php

class Post_controller extends Single_controller {
  
  public function __construct()
  {
    parent::__construct();
    $this->post = new Post_model;
  }

  public function any($parent_slug, $child_slug) {
    
    $context['post'] = $this->post->get_post_by_slug($parent_slug, $child_slug);
    
    if ($context['post']) {
      // render the view template with the context
      $this->render($context['post'], $context);
    } else {
      $this->error();
    }
    
  }
}