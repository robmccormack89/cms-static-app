<?php
namespace Rmcc; // set the Rmcc namespace for using Rmcc classes

$router->mount('/portfolio', function() use ($router) {
  
  global $query_type;
  $query_type = 'portfolio';
  
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
    Cache::cacheServe(function() use ($page) { 
      global $query_context;
      $query_context = 'MainIndexArchive';
      (new ArchiveController())->getMainIndexArchive('portfolio', true, $page);
    });
  });
  
  // portfolio index (main index url)
  $router->get('/', function() {
    Cache::cacheServe(function() { 
      global $query_context;
      $query_context = 'MainIndexArchive';
      (new ArchiveController())->getMainIndexArchive('portfolio', true);
    });
  });
  
  /*
  *
  * singular routes
  *
  */
  
  // portfolio project
  $router->get('/projects/{slug}', function($slug) {
    Cache::cacheServe(function() use ($slug) {
      global $query_context;
      $query_context = 'Single';
      (new SingleController('portfolio', $slug))->getSingle();
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

    });
    
    // portfolio technologies index (collection)
    $router->get('/', function() {

    });
  
    // portfolio technologies term index (archive)
    $router->mount('/{term}', function() use ($router) {
    
      // portfolio technologies term index paged
      $router->get('/page/{page}', function($term, $page){

      });
      
      // portfolio technologies term index (archive)
      $router->get('/', function($term){

      });
    
    });
  
  });

});

/*
*
* PORTFOLIO MAIN INDEX
*
*/
  
$router->get('/portfolio/', function() {
  Cache::cacheServe(function() { 
    global $query_type;
    $query_type = 'portfolio';
    global $query_context;
    $query_context = 'MainIndexArchive';
    (new ArchiveController())->getMainIndexArchive('portfolio', true);
  });
});

/*
*
* PORTFOLIO MAIN INDEX - PAGED
*
*/

$router->get('/portfolio/page/{page}', function($page){
 if ($page == 1) {
   header('Location: /portfolio', true, 301); // redirect requests for page one of paged archive to main archive
   exit();
 }
 Cache::cacheServe(function() use ($page) { 
   global $query_type;
   $query_type = 'portfolio';
   global $query_context;
   $query_context = 'MainIndexArchive';
   (new ArchiveController())->getMainIndexArchive('portfolio', true, $page);
 });
});

/*
*
* PORTFOLIO PROJECTS (SINGULAR)
*
*/

$router->get('/portfolio/projects/{slug}', function($slug) {
  Cache::cacheServe(function() use ($slug) {
    global $query_type;
    $query_type = 'portfolio';
    global $query_context;
    $query_context = 'Single';
    (new SingleController('portfolio', $slug))->getSingle();
  });
});

/*
*
* TECHNOLOGIES
*
*/

// collection index
// $router->get('/portfolio/technologies/', function() {
//   Cache::cacheServe(function() { 
//     (new TaxonomyArchiveController('portfolio', 'technologies', true))->getTaxCollectionArchive();
//   });
// });

// collection index - paged
// $router->get('/portfolio/technologies/page/{page}', function($page){
//   if ($page == 1) {
//     header('Location: /portfolio/technologies', true, 301);
//     exit();
//   }
//   Cache::cacheServe(function() use ($page) { 
//     (new TaxonomyArchiveController('portfolio', 'technologies', true, $page))->getTaxCollectionArchive();
//   });
// });

// term index
$router->get('/portfolio/technologies/{term}/', function($term){
  Cache::cacheServe(function() use ($term) { 
    global $query_type;
    $query_type = 'portfolio';
    global $query_context;
    $query_context = 'TaxTermArchive';
    (new ArchiveController())->getTaxTermArchive('portfolio', 'technologies', $term, true);
  });
});

// term index - paged
$router->get('/portfolio/technologies/{term}/page/{page}', function($term, $page){
 if ($page == 1) {
   header('Location: /portfolio/technologies/'.$term, true, 301); // redirect requests for page one of paged archive to main archive
   exit();
 }
 Cache::cacheServe(function() use ($term, $page) { 
   global $query_type;
   $query_type = 'portfolio';
   global $query_context;
   $query_context = 'TaxTermArchive';
   (new ArchiveController())->getTaxTermArchive('portfolio', 'technologies', $term, true, $page);
 });
});