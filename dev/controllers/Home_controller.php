<?php

class Home_controller extends Single_controller {
  
  public function __construct()
  {
    parent::__construct();
    $this->homepage = new Single_model;
  }

  public function index() {
    
    $context['single'] = $this->homepage->get_single_by_slug('index', '');
    
    if ($context['single']) {
      $this->core->render_template('homepage.twig', 'front.twig', 'home.twig', $context);
    } else {
      $this->error();
    }
    
  }
}