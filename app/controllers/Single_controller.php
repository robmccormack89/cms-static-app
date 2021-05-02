<?php

class Single_controller extends Core_controller {
  
  public function __construct() {
    parent::__construct();
    // methods here are meant for singular objects
    // $context['single'] should be defined as part of each singular method
    // the $context variable itself is what is passed on to the template via render()
    // render() is a specific renderer for singular items
    // generally all methods here will be passed on to the Single_model
    // generally all methods here require a $slug, except the homepage/index
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
  
  // check for slug-defined individual template
  // else check for type-defined template
  // else default to template for all singles: single.twig
  // uses this->twig->getLoader()->exists() from Core_controller
  // works off of $this->template_render() from Core_controller
  public function render($template_name, $context) {
    if ($this->twig->getLoader()->exists($template_name.'-'.$context['single']['slug'].'.twig')) {
      $this->template_render($template_name.'-'.$context['single']['slug'].'.twig', $context);
    } elseif ($this->twig->getLoader()->exists($template_name.'.twig')) {
      $this->template_render($template_name.'.twig', $context);
    } else {
      $this->template_render('single.twig', $context);
    };
  }
  
  // if is_published_or_draft_allowed, else throw 404 error
  public function render_check($context) {
    if (is_published_or_draft_allowed($context['single'])) {
      // render
    } else {
      $this->error();
    }
  }
  
}