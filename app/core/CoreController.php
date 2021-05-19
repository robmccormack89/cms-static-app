<?php
namespace Rmcc;

// mainly sets up the twig environment for rendering templates. includes custom caching with rendering
class CoreController {
  
  // construct twig environment, twig globals & anything else. All other controllers will extend from CoreController
  public function __construct() {
    
    // twig stuff
    $loader = new \Twig\Loader\FilesystemLoader(
      array(
        '../app/views/',
        '../app/views/archive',
        '../app/views/parts',
        '../app/views/single',
        '../app/views/single/page',
        '../app/views/single/post',
        '../app/views/single/project',
        '../app/views/parts'
      )
    );
    $this->twig = new \Twig\Environment($loader, ['cache' => '../app/cache/compilation', 'debug' => true ]);
    $this->twig->addExtension(new \Twig\Extension\DebugExtension());
    
    // twig globals
    $this->twig->addGlobal('site', SiteModel::init()->getSite());
    $this->twig->addGlobal('author', AuthorModel::init()->getAuthor());
    $this->twig->addGlobal('main_menu', new MenuModel('main-menu'));
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