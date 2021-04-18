<?php

class Single_controller extends Core_controller {
  
  public function __construct()
  {
    parent::__construct();
  }
  
  public function index() {
    $this->homepage = new Single_model;
    $context['single'] = $this->homepage->get_page('index', '');
    
    $this->render('homepage.twig', 'index.twig', $context);
  }
  
  public function page($parent_slug, $child_slug) {
    $this->page = new Single_model;
    $context['single'] = $this->page->get_page($parent_slug, $child_slug);
    
    $this->render('page-'.$context['single']['slug'].'.twig', 'page.twig', $context);
  }
  
  public function post($slug) {
    $this->post = new Single_model;
    $context['single'] = $this->post->get_post($slug);

    $this->render('post-'.$context['single']['slug'].'.twig', 'post.twig', $context);
  }
  
  public function project($slug) {
    $this->project = new Single_model;
    $context['single'] = $this->project->get_project($slug);

    $this->render('project-'.$context['single']['slug'].'.twig', 'project.twig', $context);
  }
  
  public function render($custom_template, $default_template, $context) {
    if (is_published_or_draft_allowed($context['single'])) {
      if ($this->twig->getLoader()->exists($custom_template)) {
        $this->template_render($custom_template, $context);
      } elseif ($this->twig->getLoader()->exists($default_template)) {
        $this->template_render($default_template, $context);
      } else {
        $this->template_render('single.twig', $context);
      };
    } else {
      $this->error();
    }
  }
  
}