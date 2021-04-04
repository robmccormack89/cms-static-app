<?php

class Pages_controller extends Core_controller {
  
  public function __construct()
  {
    parent::__construct();
  }

  public function any($slug) {
    
    $page = new Page;
    $reqPage = $page->get_page_by_slug($slug);
    $movies = new Movies;
    $context['movies'] = $movies->get_movies();
    
    // if the page requested actually exists
    if ($reqPage && $reqPage['status'] == 'published' || $reqPage && $reqPage['status'] == 'draft' && REMOTE_ADDR == AUTHOR_IP) {

      $context['page'] = $reqPage;

      // if custom page template exists, use that
      if ($this->twig->getLoader()->exists('page-'.$slug.'.twig')) {
        
        $this->cache->cacheServe();
        echo $this->twig->render('page-'.$slug.'.twig', $context);
        $this->cache->cacheFile();
        
      } else {
        
        // render the default page template
        $this->cache->cacheServe();
        echo $this->twig->render('page.twig', $context);
        $this->cache->cacheFile();
        
      }
      
    } else {
      
      // else, render the 404 template
      echo $this->twig->render('404.twig');
      
    }
    
    
  }
  
}