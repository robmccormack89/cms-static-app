<?php

class Core_controller {
  
  public function __construct() {
    
    // directories to find twig files in
    $loader = new \Twig\Loader\FilesystemLoader(
      array(
        '../app/views/',
        '../app/views/archive',
        '../app/views/parts',
        '../app/views/single',
        '../app/views/parts'
      )
    );
    // twig environment settings
    $this->twig = new \Twig\Environment($loader, [
      'cache' => '../app/cache/compilation',
      'debug' => true
    ]);
    // debug extension
    $this->twig->addExtension(new \Twig\Extension\DebugExtension());
    
    // configs -> twig global
    $this->twig->addGlobal('configs', $GLOBALS['configs'] );
    // cpts & taxes -> twig global
    $this->twig->addGlobal('is_blog', is_blog());
    $this->twig->addGlobal('is_post', is_post());
    $this->twig->addGlobal('is_category', is_category());
    $this->twig->addGlobal('is_tag', is_tag());
    $this->twig->addGlobal('is_portfolio', is_portfolio());
    $this->twig->addGlobal('is_project', is_project());
    // menus -> twig global
    $this->menu = new Site_model;
    $this->twig->addGlobal('main_menu', $this->menu->get_menu('main-menu') );
    
  }

  // 404 error pages
  public function error() {
    echo $this->twig->render('404.twig');
  }
  
  // core renderer with caching. use this function for specific renders per frontend context (Archive_controller or Single_controller)
  public function template_render($template, $context) {
    cache_render($this->twig->render($template, $context));
  }
  
}