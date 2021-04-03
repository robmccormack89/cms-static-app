<?php

class Pages_controller extends Core_controller {
  
  public function __construct()
  {
    parent::__construct();
  }

  public function any($slug) {
    
    $page = new Page;
    $reqPage = $page->get_page_by_slug($slug);
    
    $fruit = new Fruit;
    $cars = new Cars;
    $movies = new Movies;
    $context['fruits'] = $fruit->get_fruit();
    $context['cars'] = $cars->get_cars();
    $context['movies'] = $movies->get_movies();
    // $context['app_protocol'] = APP_PROTOCOL;
    
    // if the page requested actually exists
    if ($reqPage) {
      // assign the page variable to twig context & render page.twig
      $context['page'] = $reqPage;
      $myIP = $_SERVER['REMOTE_ADDR'];
      $context['myIP'] = $myIP;
      if ($reqPage['status'] === 'published' || $myIP === '127.0.0.1') {
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
    } else {
      // else, render the 404 template
      echo $this->twig->render('404.twig');
    }
    
  }
  
}