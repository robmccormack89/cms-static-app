<?php

class Core_controller {
  
  public $configs;
  protected $twig;
  protected $cache;
  protected $is_cache_enabled;
  
  public function __construct()
  {
    // include the config file
    $configs = include('../app/config/config.php');

    // dark light mode, for setting on page load rather than using js. Js now only applies the dark light switch clicks
    $dark_light_def = '';
    if(isset($_COOKIE['darklightswitch'])) {
      $dark_light_def = array(
        'body_class' => 'uk-light',
        'sun_link_show_hide' => '',
        'moon_link_show_hide' => 'hidden',
      );
    } elseif (!isset($_COOKIE['darklightswitch'])) {
      $dark_light_def = array(
        'body_class' => '',
        'sun_link_show_hide' => 'hidden',
        'moon_link_show_hide' => '',
      );    
    }

    // directories to find twig files in
    $views = array(
      '../app/views/',
      '../app/views/pages',
      '../app/views/pages/content',
      '../app/views/posts',
      '../app/views/posts/content',
      '../app/views/parts'
    );
    $loader = new \Twig\Loader\FilesystemLoader($views);
    // twig environment setup with compilation cache & debug enabled
    $this->twig = new \Twig\Environment($loader, [
      'cache' => '../app/cache/compilation',
      'debug' => true
    ]);
    $this->twig->addExtension(new \Twig\Extension\DebugExtension());
    // add config settings to the global twig context
    $this->twig->addGlobal('configs', $configs );
    $this->twig->addGlobal('dark_light_def', $dark_light_def );
    
    // include caching class & create new cache object. Plus check for cache enabled setting
    require_once('../app/config/cache.php');
    $this->cache = new Cache;
    $is_cache_enabled = ($this->configs['php_cache'] == 'enable');

  }
  
  // call this function in routes for the error page
  public function error() {
    echo $this->twig->render('404.twig');
  }
  
}