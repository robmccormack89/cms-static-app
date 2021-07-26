<?php
namespace Rmcc; // set the Rmcc namespace for using Rmcc classes

/*
*
* Search/replace index: 'BLOG' & 'blog' 
* Search/replace singular: 'POSTS' & 'posts'
* Search/replace taxonomy: 'CATEGORIES', 'categories'
* Search/replace taxonomy: 'TAGS', 'tags'
* 
*
*/

/*
*
* BLOG MAIN INDEX
*
*/
  
$router->get('/blog/', function() {
  Cache::cacheServe(function() { 
    (new ArchiveController())->getMainIndexArchive('blog', true);
  });
});

/*
*
* BLOG MAIN INDEX - PAGED
*
*/

$router->get('/blog/page/{page}', function($page){
 if ($page == 1) {
   header('Location: /blog', true, 301); // redirect requests for page one of paged archive to main archive
   exit();
 }
 Cache::cacheServe(function() use ($page) { 
   (new ArchiveController())->getMainIndexArchive('blog', true, $page);
 });
});

/*
*
* BLOG POSTS (SINGULAR)
*
*/

$router->get('/blog/posts/{slug}', function($slug) {
  Cache::cacheServe(function() use ($slug) { 
    (new SingleController('blog', $slug))->getSingle();
  });
});

/*
*
* CATEGORIES
*
*/

// collection index
// $router->get('/blog/categories/', function() {
//   Cache::cacheServe(function() { 
//     (new ArchiveController())->getTaxCollectionArchive('blog', 'categories', true);
//   });
// });

// collection index - paged
// $router->get('/blog/categories/page/{page}', function($page){
//   if ($page == 1) {
//     header('Location: /blog/categories', true, 301);
//     exit();
//   }
//   Cache::cacheServe(function() use ($page) { 
//     (new ArchiveController())->getTaxCollectionArchive('blog', 'categories', true, $page);
//   });
// });

// term index - paged
$router->get('/blog/categories/{term}/page/{page}', function($term, $page){
 if ($page == 1) {
   header('Location: /blog/categories/'.$term, true, 301); // redirect requests for page one of paged archive to main archive
   exit();
 }
 Cache::cacheServe(function() use ($term, $page) { 
   (new ArchiveController())->getTaxTermArchive('blog', 'categories', $term, true, $page);
 });
});

// term index
$router->get('/blog/categories/{term}/', function($term){
  Cache::cacheServe(function() use ($term) { 
    (new ArchiveController())->getTaxTermArchive('blog', 'categories', $term, true);
  });
});

/*
*
* TAGS
*
*/

// collection index
// $router->get('/blog/tags/', function() {
//   Cache::cacheServe(function() { 
//     (new ArchiveController())->getTaxCollectionArchive('blog', 'tags', true);
//   });
// });

// collection index - paged
// $router->get('/blog/tags/page/{page}', function($page){
//   if ($page == 1) {
//     header('Location: /blog/tags', true, 301);
//     exit();
//   }
//   Cache::cacheServe(function() use ($page) { 
//     (new ArchiveController())->getTaxCollectionArchive('blog', 'tags', true, $page);
//   });
// });

// term index - paged
$router->get('/blog/tags/{term}/page/{page}', function($term, $page){
 if ($page == 1) {
   header('Location: /blog/tags/'.$term, true, 301); // redirect requests for page one of paged archive to main archive
   exit();
 }
 Cache::cacheServe(function() use ($term, $page) { 
   (new ArchiveController())->getTaxTermArchive('blog', 'tags', $term, true, $page);
 });
});

// term index
$router->get('/blog/tags/{term}/', function($term){
  Cache::cacheServe(function() use ($term) { 
    (new ArchiveController())->getTaxTermArchive('blog', 'tags', $term, true);
  });
});