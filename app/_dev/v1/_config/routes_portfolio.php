<?php

// for routing for new types, copy this file into config folder & rename according to new type-key. e.g routes_portfolio.php
// then search/replace the old type-key(portfolio) in this file with your new type-key e.g portfolio
// data for the content type must exist in json file firstly
// config.php -> type_settings must contain item for your new content type secondly
// dont forget to load the new routes up in routes.php at the end.

// Strings to replace: 'portfolio', 'project/projects'

/*
*
* The portfolio routes. with index_uri = /portfolio
*
*/

$router->mount('/portfolio', function() use ($router) {
  
  /*
  *
  * main index archive routes
  *
  */
  
  // portfolio index (paged urls. go before main index url)
  $router->get('/page/{page}', function($page){
   if ($page == 1) {
     header('Location: /portfolio', true, 301); // redirect requests for page one of paged archive to main archive
     exit();
   }
   Rmcc\Cache::cacheServe(function() use ($page) { 
     $portfolio = new Rmcc\ArchiveController('portfolio', true, $page);
     $portfolio->getMainIndexArchive();
   });
  });
  
  // portfolio index (main index url)
  $router->get('/', function() {
    Rmcc\Cache::cacheServe(function() { 
      $portfolio = new Rmcc\ArchiveController('portfolio', true);
      $portfolio->getMainIndexArchive();
    });
  });
  
  /*
  *
  * singular routes
  *
  */
  
  // portfolio project
  $router->get('/projects/{slug}', function($slug) {
    Rmcc\Cache::cacheServe(function() use ($slug) { 
      $project = new Rmcc\SingleController('portfolio', $slug);
      $project->getSingle();
    });
  });
  
  /*
  *
  * taxonomy routes
  *
  */
  
  // portfolio technologies (archive)
  $router->mount('/technologies', function() use ($router) {
    
    // portfolio technologies index paged (collection)
    $router->get('/page/{page}', function($page){
      if ($page == 1) {
        header('Location: /portfolio/technologies', true, 301);
        exit();
      }
      Rmcc\Cache::cacheServe(function() use ($page) { 
        $tech_collection = new Rmcc\CollectionArchiveController('portfolio', 'technologies', true, $page);
        $tech_collection->getTaxCollectionArchive();
      });
    });
    
    // portfolio technologies index (collection)
    $router->get('/', function() {
      Rmcc\Cache::cacheServe(function() { 
        $tech_collection = new Rmcc\CollectionArchiveController('portfolio', 'technologies',   true);
        $tech_collection->getTaxCollectionArchive();
      });
    });
  
    // portfolio technologies term index (archive)
    $router->mount('/{term}', function() use ($router) {
    
      // portfolio technologies term index paged
      $router->get('/page/{page}', function($term, $page){
       if ($page == 1) {
         header('Location: /portfolio/technologies/'.$term, true, 301); // redirect requests for page one of paged archive to main archive
         exit();
       }
       Rmcc\Cache::cacheServe(function() use ($term, $page) { 
         $technology = new Rmcc\TermArchiveController('portfolio', 'technologies', $term, true, $page);
         $technology->getTaxTermArchive();
       });
      });
      
      // portfolio technologies term index (archive)
      $router->get('/', function($term){
        Rmcc\Cache::cacheServe(function() use ($term) { 
          $technology = new Rmcc\TermArchiveController('portfolio', 'technologies', $term, true);
          $technology->getTaxTermArchive();
        });
      });
    
    });
  
  });

});