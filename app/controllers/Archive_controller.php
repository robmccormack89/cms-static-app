<?php

class Archive_controller extends Core_controller {
  
  public $paged;
  
  public function __construct($paged)
  {
    parent::__construct();
    $this->page = $paged;
  }
  public function blog() {
    $blog = new Archive_model($this->page, 'blog');
    $context['archive'] = $blog->get_archive_data()->meta;
    $context['archive']['routes'] = $blog->get_archive_data()->routes;
    $context['archive']['posts'] = $blog->get_posts();
    $context['archive']['pagination'] = $blog->get_pagination();
    
    if ($context['archive']['posts']) {
      $this->render_archive($context['archive'], 'blog.twig', 'news.twig', 'archive.twig', $context);
    } 
    else {
      $this->error();
    }
  }
  public function portfolio() {
    $portfolio = new Archive_model($this->page, 'portfolio');
    $context['archive'] = $portfolio->get_archive_data()->meta;
    $context['archive']['routes'] = $portfolio->get_archive_data()->routes;
    $context['archive']['posts'] = $portfolio->get_posts();
    $context['archive']['pagination'] = $portfolio->get_pagination();
    
    if ($context['archive']['posts']) {
      $this->render_archive($context['archive'], 'portfolio.twig', 'projects.twig', 'archive.twig', $context);
    } 
    else {
      $this->error();
    }
  }
}