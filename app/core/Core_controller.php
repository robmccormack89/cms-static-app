<?php

class Core_controller {
  
  protected $twig;
  public $site; 
  
  public function __construct()
  {    
    
    $theSite = new Site;

    $views = array(
      '../app/views/',
      '../app/views/pages',
      '../app/views/pages/content',
      '../app/views/parts'
    );
    $loader = new \Twig\Loader\FilesystemLoader($views);

    $this->twig = new \Twig\Environment($loader, [
      'cache' => '../app/cache',
      'debug' => true,
      // ...
    ]);
    $this->twig->addExtension(new \Twig\Extension\DebugExtension());
    
    $site = $theSite->get_site();
    
    $SiteTitle = 'Your Site Title';
    $SomeOtherVariable = 'Lorem Ipsum Dolor';
    $dateYear = date("Y");
    
    $this->twig->addGlobal('SiteTitle', $SiteTitle);
    $this->twig->addGlobal('SomeOtherVariable', $SomeOtherVariable);
    $this->twig->addGlobal('date_year', $dateYear );
    $this->twig->addGlobal('site', $site );
    
    // if (APP_PROTOCOL == "https") {
    //   if ($_SERVER['HTTPS'] != 'on') {
    //     $url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    //     header("Location: https://static.com/", true, 301);
    //     exit();
    //   }
    // }
    
  }
  
  public function error() {
    echo $this->twig->render('404.twig');
  }
  
  // public function about() {
  // 
  //   $fruit = new Fruit;
  //   $cars = new Cars;
  //   $movies = new Movies;
  //   $context['fruits'] = $fruit->get_fruit();
  //   $context['cars'] = $cars->get_cars();
  //   $context['movies'] = $movies->get_movies();
  // 
  //   $context['title'] = 'About Page';
  // 
  //   $template = $this->twig->load('about.twig');
  //   echo $template->render($context);
  // }
  
}
// 
// $Core_controller = new Core_controller;