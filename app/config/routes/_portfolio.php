<?php
namespace Rmcc; // set the Rmcc namespace for using Rmcc classes

/*
*
* PORTFOLIO MAIN INDEX
*
*/
$router->get('/portfolio/', function() {
  $params = parse_url($_SERVER['REQUEST_URI']);
  if (isset($params['query']) && queryParamsExists($params['query'])) {
    parse_str($params['query'], $params_array);
    if($_SERVER['REQUEST_URI'] === '/portfolio?p=1'){
      header('Location: /portfolio', true, 301);
      exit();
    }
    (new ArchiveController('portfolio'))->queryMainIndexArchive($params['query']);
  } else {
    Cache::cacheServe(function(){ 
      (new ArchiveController('portfolio'))->getMainIndexArchive();
    });
  }
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

// term index
$router->get('/portfolio/technologies/{term}/', function($term){
   $params = parse_url($_SERVER['REQUEST_URI']);
   if (isset($params['query']) && queryParamsExists($params['query'])) {
     parse_str($params['query'], $params_array);
     if($_SERVER['REQUEST_URI'] === '/portfolio/technologies/'.$term.'?p=1'){
       header('Location: /portfolio/technologies/'.$term, true, 301); // redirect requests for page one of paged archive to main archive
       exit();
     }
     (new ArchiveController('portfolio'))->queryTaxTermArchive($params['query'], 'technologies', $term);
   } else {
     Cache::cacheServe(function() use ($term) { 
       (new ArchiveController('portfolio'))->getTaxTermArchive('technologies', $term);
     });
   } 
});

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