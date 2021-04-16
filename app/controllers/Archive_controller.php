<?php

class Archive_controller extends Core_controller {
  
  public function __construct()
  {
    parent::__construct();
  }
  
  public function blog($page) {
    $this->blog = new Archive_model;
    $context['archive'] = $this->blog->posts($page)->archive;
    $context['archive']['posts'] = $this->blog->posts($page)->posts;
    
    if ($context['archive']['posts']) {
      $this->render_archive($context['archive'], 'blog.twig', 'archive.twig', '404.twig', $context);
    } 
    else {
      $this->error();
    }
  }
  
  public function portfolio() {
    $this->portfolio = new Archive_model;
    $context['archive'] = $this->portfolio->projects();
    // set the project link url
    foreach ($context['archive']['projects'] as $post) {
      $post['link'] = $GLOBALS['configs']['base_url'].$GLOBALS['configs']['project_url'].'/'.$post['slug'];
      $posts[] = $post;
    }
    // set the archive.posts context from modified above posts
    $context['archive']['posts'] = $posts;

    $this->render_archive($context['archive'], 'blog.twig', 'archive.twig', '404.twig', $context);
  }
  
}