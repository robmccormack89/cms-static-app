<?php

class Single_controller extends Core_controller {
  
  public function __construct()
  {
    parent::__construct();
  }
  
  public function index() {
    $this->homepage = new Single_model;
    $context['single'] = $this->homepage->get_single_by_slug('index', '');
    
    $this->render_single($context['single'], 'homepage.twig', 'front.twig', 'home.twig', $context);
  }
  
  public function page($parent_slug, $child_slug) {
    $this->page = new Single_model;
    $context['single'] = $this->page->get_single_by_slug($parent_slug, $child_slug);
    
    $this->render_single($context['single'], 'page-'.$context['single']['slug'].'.twig', 'page.twig', 'single.twig', $context);
  }
  
  public function post($slug) {
    $this->post = new Single_model;
    $context['single'] = $this->post->get_post_by_slug($slug);

    $this->render_single($context['single'], 'post-'.$context['single']['slug'].'.twig', 'post.twig', 'single.twig', $context);
  }
  
  public function project($slug) {
    $this->project = new Single_model;
    $context['single'] = $this->project->get_project_by_slug($slug);

    $this->render_single($context['single'], 'project-'.$context['single']['slug'].'.twig', 'project.twig', 'single.twig', $context);
  }
  
}