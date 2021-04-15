<?php

class Core_model {
  
  public $singles;
  
  public function __construct()
  {
    // include caching class & create new cache object
    require_once('../app/config/cache.php');
    $this->cache = new Cache;
    // twig environment setup with compilation cache & debug extension
    $loader = new \Twig\Loader\FilesystemLoader(
      // directories to find twig files in
      array(
        '../app/views/',
        '../app/views/archive',
        '../app/views/parts',
        '../app/views/single',
        '../app/views/parts'
      )
    );
    $this->twig = new \Twig\Environment($loader, [
      // twig environment settings
      'cache' => '../app/cache/compilation',
      'debug' => true
    ]);
    $this->twig->addExtension(new \Twig\Extension\DebugExtension());
    // config settings -> twig global
    $this->twig->addGlobal('configs', $GLOBALS['configs'] );
    // darklight_cookie -> twig global
    $this->twig->addGlobal('dark_light_def', darklight_cookie());
    // menus -> twig global
    $this->menu = new Menu_model;
    $this->twig->addGlobal('main_menu', $this->menu->get_menu_by_slug('main-menu') );
    
    $this->singles = get_json_data('singles');
  }
  
  // a custom renderer for twig, $context & custom caching
  // check if 1st template arg exists in twig loader, then 2nd template, then falls back to third.
  // renders relevant twig template with context & caching accoring to setting
  public function render_template($custom_template, $default_template, $base_template, $context) {
    if ($this->twig->getLoader()->exists($custom_template)) {
      $this->cache_render($custom_template, $context);
    } elseif ($this->twig->getLoader()->exists($default_template)) {
      $this->cache_render($default_template, $context);
    } else {
      $this->cache_render($base_template, $context);
    }
  }
  public function cache_render($the_template, $context) {
    if(is_cache_enabled()): $this->cache->cacheServe(); endif;
    echo $this->twig->render($the_template, $context);
    if(is_cache_enabled()): $this->cache->cacheFile(); endif;
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