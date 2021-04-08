<?php

class Home_controller extends Single_controller {
  
  public function __construct()
  {
    parent::__construct();
    $this->page = new Single_model;
  }

  public function index() {
    
    $context['page'] = $this->page->get_single_by_slug('index', '');
    
    if ($context['page']) {
      // render the view template with the context
      $this->render_template('', 'front.twig', $context);
    } else {
      $this->error();
    }
    
  }
}