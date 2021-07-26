<?php
namespace Rmcc; // set the Rmcc namespace for using Rmcc classes

/*
*
* PORTFOLIO MAIN INDEX
*
*/
  
$router->get('/portfolio/', function() {
  Cache::cacheServe(function() { 
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
   (new ArchiveController())->getTaxTermArchive('portfolio', 'technologies', $term, true, $page);
 });
});