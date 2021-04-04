<?php

class Home_controller extends Core_controller {
  
  public function __construct()
  {
    parent::__construct();
  }

  public function index() {
    $context['title'] = 'Homepage';
    
    $page = new Page;
    $homePage = $page->get_page_by_slug('index');
    
    // if the page requested actually exists
    if ($homePage) {
      // assign the page variable to twig context & render page.twig
      $context['page'] = $homePage;
      $this->cache->cacheServe();
      echo $this->twig->render('front.twig', $context);
      $this->cache->cacheFile();
    } else {
      // else, render the 404 template
      echo $this->twig->render('404.twig');
    }
  }
  
}