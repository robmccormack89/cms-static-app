<?php

class Pages_controller extends Core_controller {
  
  public function __construct()
  {
    parent::__construct();
  }

  public function any($slug) {
    
    $page = new Page;
    $reqPage = $page->get_page_by_slug($slug);
    // $movies = new Movies;
    // $context['movies'] = $movies->get_movies();
    
    $is_page_published = ($reqPage && $reqPage['status'] == 'published');
    $is_page_draft_allowed = ($reqPage && $reqPage['status'] == 'draft' && $this->configs['visitor_ip'] == $this->configs['author_ip']);
    $custom_page_template_exists = ($this->twig->getLoader()->exists('page-'.$slug.'.twig'));
    
    // if the page requested actually exists
    if ( $is_page_published || $is_page_draft_allowed ) {

      $context['page'] = $reqPage;

      // if custom page template exists, use that
      if ($custom_page_template_exists) {
        
        if ($this->is_cache_enabled) { $this->cache->cacheServe(); }
        echo $this->twig->render('page-'.$slug.'.twig', $context);
        if ($this->is_cache_enabled) { $this->cache->cacheFile(); }
        
      } else {
        
        // render the default page template
        if ($this->is_cache_enabled) { $this->cache->cacheServe(); }
        echo $this->twig->render('page.twig', $context);
        if ($this->is_cache_enabled) { $this->cache->cacheFile(); }
        
      }
    }
    
    if (!$reqPage) {
      // else, render the 404 template
      $this->error();
    }
    
  }
}