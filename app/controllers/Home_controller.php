<?php

class Home_controller extends Single_controller {
  
  public function __construct()
  {
    parent::__construct();
    $this->page = new Single_model;
  }

  public function index() {
    
    $context['single'] = $this->page->get_single_by_slug('index', '');
    
    if ($context['single']) {
      // render the view template with the context
      $this->render_template('homepage.twig', 'front.twig', 'home.twig', $context);
    } else {
      $this->error();
    }
    
  }
}