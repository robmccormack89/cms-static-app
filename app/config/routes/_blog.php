<?php
namespace Rmcc;

/*
*
* Main Index Archive
*
*/
$router->get('/blog/', function() {
  Cache::cacheServe(function(){ 
    (new ArchiveController('blog'))->getMainIndexArchive();
  });
});

/*
*
* Main Index Archive: Paged
*
*/
$router->get('/blog/page/{page}', function($page){
 if ($page == 1) {
   header('Location: /blog', true, 301);
   exit();
 }
 Cache::cacheServe(function() use ($page) { 
   (new ArchiveController('blog', $page))->getMainIndexArchive();
 });
});

/*
*
* Singles
*
*/
$router->get('/blog/posts/{slug}', function($slug) {
  Cache::cacheServe(function() use ($slug) { 
    global $query_type;
    $query_type = 'blog';
    global $query_context;
    $query_context = 'Single';
    (new SingleController('blog', $slug))->getSingle();
  });
});

/*
*
* Term Archives: Paged
*
*/
$router->get('/blog/categories/{term}/page/{page}', function($term, $page){
 if ($page == 1) {
   header('Location: /blog/categories/'.$term, true, 301); // redirect requests for page one of paged archive to main archive
   exit();
 }
 Cache::cacheServe(function() use ($term, $page) { 
   (new ArchiveController('blog', $page))->getTaxTermArchive('categories', $term);
 });
});

/*
*
* Term Archives
*
*/
$router->get('/blog/categories/{term}/', function($term){
  Cache::cacheServe(function() use ($term) { 
    (new ArchiveController('blog'))->getTaxTermArchive('categories', $term);
  });
});

/*
*
* Term Archives: Paged
*
*/
$router->get('/blog/tags/{term}/page/{page}', function($term, $page){
 if ($page == 1) {
   header('Location: /blog/tags/'.$term, true, 301); // redirect requests for page one of paged archive to main archive
   exit();
 }
 Cache::cacheServe(function() use ($term, $page) {
   (new ArchiveController('blog', $page))->getTaxTermArchive('tags', $term);
 });
});

/*
*
* Term Archives
*
*/
$router->get('/blog/tags/{term}/', function($term){
  Cache::cacheServe(function() use ($term) { 
    (new ArchiveController('blog'))->getTaxTermArchive('tags', $term);
  });
});

// collection indexs
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