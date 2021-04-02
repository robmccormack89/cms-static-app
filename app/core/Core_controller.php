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
      '../app/views/parts'
    );
    $loader = new \Twig\Loader\FilesystemLoader($views);

    $this->twig = new \Twig\Environment($loader, [
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
  }
  
  public function any($slug) {
    
    $page = new Page;
    $reqPage = $page->get_page_by_slug($slug);
    
    // if the page requested actually exists
    if ($reqPage) {
      // assign the page variable to twig context & render page.twig
      $context['page'] = $reqPage;
      // if custom page template exists
      if ($this->twig->getLoader()->exists('page-'.$slug.'.twig')) {
        echo $this->twig->render('page-'.$slug.'.twig', $context);
      } else {
        // render the default page template
        echo $this->twig->render('page.twig', $context);
      }
    } else {
      // else, render the 404 template
      echo $this->twig->render('404.twig');
    }
    
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