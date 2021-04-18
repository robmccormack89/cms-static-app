<?php

class Core_controller {
  
  public function __construct() {
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
  }

  public function error() {
    echo $this->twig->render('404.twig');
  }

  public function template_render($template, $context) {
    if(is_cache_enabled()): $this->cache->cacheServe(); endif;
    echo $this->twig->render($template, $context);
    if(is_cache_enabled()): $this->cache->cacheFile(); endif;
  }
  
}