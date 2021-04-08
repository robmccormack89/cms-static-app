<?php

class Single_controller extends Core_controller {
  
  public $single;
  
  public function __construct()
  {
    parent::__construct();
    $this->single = new Single_model;
  }

  public function any($parent_slug, $child_slug) {
    
    $context['single'] = $this->single->get_single_by_slug($parent_slug, $child_slug);
    
    if ($context['single']) {
      // render the view template with the context
      $this->render($context['single'], $context);
    } else {
      $this->error();
    }
    
  }
  
  // custom templates according to type
  public function render($obj, $context) {
    if ($obj['type'] == 'post') {
      $this->render_template('post-'.$obj['slug'].'.twig', 'post.twig', $context);
    } else {
      $this->render_template('single-'.$obj['slug'].'.twig', 'single.twig', $context);
    };
  }
  // twig render if custom template exists, with custom caching according to setting in config.php
  public function render_template($custom_template, $default_template, $context) {
    
    if ($this->twig->getLoader()->exists($custom_template)) {
      
      if(is_cache_enabled()): $this->cache->cacheServe(); endif;
      echo $this->twig->render($custom_template, $context);
      if(is_cache_enabled()): $this->cache->cacheFile(); endif;
      
    } else {
      
      if(is_cache_enabled()): $this->cache->cacheServe(); endif;
      echo $this->twig->render($default_template, $context);
      if(is_cache_enabled()): $this->cache->cacheFile(); endif;
      
    }
  }
  
}