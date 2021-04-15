<?php

class Archive_controller extends Core_controller {
  
  public function __construct()
  {
    parent::__construct();
  }
  
  public function blog() {
    $this->blog = new Archive_model;
    $context['archive'] = $this->blog->posts();
    // set the post link url
    foreach ($context['archive']['posts'] as $post) {
      $post['link'] = $GLOBALS['configs']['base_url'].$GLOBALS['configs']['post_url'].'/'.$post['slug'];
      $posts[] = $post;
    }
    // set the archive.posts context from modified above posts
    $context['archive']['posts'] = $posts;

    $this->render_archive($context['archive'], 'blog.twig', 'archive.twig', '404.twig', $context);
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