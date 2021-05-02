<?php

class Single_controller extends Core_controller {
  
  public function __construct() {
    parent::__construct();
  }
  
  // homepage
  public function index() {
    $this->homepage = new Single_model('page', 'index');
    $context['single'] = $this->homepage->get_single();
    
    $this->render('index', $context);
  }
  // page
  public function page($slug) {
    $this->page = new Single_model('page', $slug);
    $context['single'] = $this->page->get_single();
    
    $this->render('page', $context);
  }
  // post
  public function post($slug) {
    $this->post = new Single_model('post', $slug);
    $context['single'] = $this->post->get_single();

    $this->render('post', $context);
  }
  // project
  public function project($slug) {
    $this->project = new Single_model('project', $slug);
    $context['single'] = $this->project->get_single();

    $this->render('project', $context);
  }
  
  // custom renderer with is_single_allowed check
  // status = 'published' | status = 'draft' & visitor_ip = author_ip
  public function render($template_name, $context) {
    if (is_single_allowed($context['single'])) {
      
      $slug = slug_to_filename($context['single']['slug']);
      
      if ($this->twig->getLoader()->exists($template_name.'-'.$slug.'.twig')) {
        $this->template_render($template_name.'-'.$slug.'.twig', $context);
      } elseif ($this->twig->getLoader()->exists($template_name.'.twig')) {
        $this->template_render($template_name.'.twig', $context);
      } else {
        $this->template_render('single.twig', $context);
      };
    } else {
      $this->error();
    }
  }
  
}