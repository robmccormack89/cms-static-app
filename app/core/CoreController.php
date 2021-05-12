<?php
namespace Rmcc;

class CoreController {
  
  public function __construct() {
    
    // directories to find twig files in
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
    // twig environment settings
    $this->twig = new \Twig\Environment($loader, [
      // 'cache' => '../app/cache/compilation',
      'debug' => true
    ]);
    // debug extension
    $this->twig->addExtension(new \Twig\Extension\DebugExtension());
    
    // configs -> twig global
    $site = SiteModel::init();
    $this->site = $site->getSite();
    $this->twig->addGlobal('site', $this->site);
    
    $author = AuthorModel::init();
    $this->author = $author->getAuthor();
    $this->twig->addGlobal('author', $this->author);
    
    $this->main_menu = new MenuModel('main-menu');
    $this->twig->addGlobal('main_menu', $this->main_menu);
    
    $this->twig->addGlobal('base_url', $GLOBALS['config']['base_url']);
    $this->twig->addGlobal('current_url', $GLOBALS['config']['base_url'].$_SERVER['REQUEST_URI']);
  }

  // 404 error pages
  public function error() {
    echo $this->twig->render('404.twig');
  }
  
}