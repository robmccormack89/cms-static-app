<?php
namespace Rmcc;

// mainly sets up the twig environment for rendering templates. includes custom caching with rendering
class CoreController {
  
  // construct twig environment, twig globals & anything else. All other controllers will extend from CoreController
  public function __construct() {
      
    // enable for error reporting in cases of fatal errors
    // ini_set('display_errors', '1');
    // ini_set('display_startup_errors', '1');
    // error_reporting(E_ALL);
    
    // twig stuff
    $loader = new \Twig\Loader\FilesystemLoader(
      array(
        '../app/views/',
        '../app/views/_one',
        '../app/views/_one/parts',
        '../app/views/_two',
        '../app/views/_two/parts',
        '../app/views/archive',
        '../app/views/parts',
        '../app/views/single',
        '../app/views/single/page',
        '../app/views/single/post',
        '../app/views/single/project',
        '../app/views/parts',
      )
    );
    $this->twig = new \Twig\Environment($loader, ['cache' => '../app/cache/compilation', 'debug' => true ]);
    $this->twig->addExtension(new \Twig\Extension\DebugExtension());
    
    // twig globals
    $this->twig->addGlobal('site', SiteModel::init()->getSite());
    $this->twig->addGlobal('author', AuthorModel::init()->getAuthor());
    $main_menu = new MenuModel('main-menu');
    $main_menu_chunked = array_chunk($main_menu->menu_items, ceil(count($main_menu->menu_items) / 2));
    $main_menu_first = $main_menu_chunked[0];
    $main_menu_second = $main_menu_chunked[1];
    $this->twig->addGlobal('main_menu', $main_menu);
    $this->twig->addGlobal('main_menu_first', $main_menu_first);
    $this->twig->addGlobal('main_menu_second', $main_menu_second);
    $this->twig->addGlobal('base_url', $GLOBALS['config']['base_url']);
    $this->twig->addGlobal('current_url', $GLOBALS['config']['base_url'].$_SERVER['REQUEST_URI']);
  }

  // 404 errors
  public function error() {
    echo $this->twig->render('404.twig');
  }
  
  // render twig template with custom caching step-in
  protected function templateRender($template, $context) {
    Cache::cacheRender($this->twig->render($template, $context));
  }
  
}