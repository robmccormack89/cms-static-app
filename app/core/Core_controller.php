<?php

class Core_controller {

  protected $twig;
  protected $cache;
  
  public function __construct()
  {
    // include caching class & create new cache object.
    require_once('../app/config/cache.php');
    $this->cache = new Cache;
    
    // directories to find twig files in
    $views = array(
      '../app/views/',
      '../app/views/archive',
      '../app/views/parts',
      '../app/views/single',
      '../app/views/parts'
    );
    $loader = new \Twig\Loader\FilesystemLoader($views);
    // twig environment setup with compilation cache & debug enabled
    $this->twig = new \Twig\Environment($loader, [
      'cache' => '../app/cache/compilation',
      'debug' => true
    ]);
    $this->twig->addExtension(new \Twig\Extension\DebugExtension());
    // twig globals
    $this->twig->addGlobal('configs', $GLOBALS['configs'] );
    $this->twig->addGlobal('dark_light_def', darklight_cookie());
  }
  
  // call this function in routes for the error page
  public function error() {
    echo $this->twig->render('404.twig');
  }
  
  // a custom renderer for twig, $context & custom caching
  // check if 1st template arg exists in twig loader, then 2nd template, then falls back to third.
  // renders relevant twig template with context & caching accoring to setting
  public function render_template($custom_template, $default_template, $base_template, $context) {
    if ($this->twig->getLoader()->exists($custom_template)) {
      if(is_cache_enabled()): $this->cache->cacheServe(); endif;
      echo $this->twig->render($custom_template, $context);
      if(is_cache_enabled()): $this->cache->cacheFile(); endif;
    } elseif ($this->twig->getLoader()->exists($default_template)) {
      if(is_cache_enabled()): $this->cache->cacheServe(); endif;
      echo $this->twig->render($default_template, $context);
      if(is_cache_enabled()): $this->cache->cacheFile(); endif;
    } else {
      if(is_cache_enabled()): $this->cache->cacheServe(); endif;
      echo $this->twig->render($base_template, $context);
      if(is_cache_enabled()): $this->cache->cacheFile(); endif;
    }
  }
  // custom singular templates according to type
  public function render_single($obj, $context) {
    if (is_page_post_or_project($obj)) {
      $this->render_template($obj['type'].'-'.$obj['slug'].'.twig', $obj['type'].'.twig', 'single.twig', $context);
    } else {
      $this->render_template('single-'.$obj['slug'].'.twig', 'single.twig', '404.twig', $context);
    };
  }
  // custom archive templates according to archive name
  public function render_archive($obj, $context) {
    if ($obj['name'] == 'blog') {
      $this->render_template('blog.twig', 'archive.twig', '404.twig', $context);
    } elseif ($obj['name'] == 'portfolio') {
      $this->render_template('portfolio.twig', 'archive.twig', '404.twig', $context);
    };
  }
  
}