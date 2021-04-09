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
      '../app/views/single/content',
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
    $this->twig->addGlobal('dark_light_def', darklight_cookie_check());
  }
  
  // call this function in routes for the error page
  public function error() {
    echo $this->twig->render('404.twig');
  }
  
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
  
}