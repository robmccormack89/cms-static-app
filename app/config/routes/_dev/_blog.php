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

$router->mount('/blog', function() use ($router) {
  
  global $query_type;
  $query_type = 'blog';
  
  /*
  *
  * main index archive routes
  *
  */
  
  // blog index (paged urls. go before main index url)
  $router->get('/page/{page}', function($page){
    if ($page == 1) {
      header('Location: /blog', true, 301); // redirect requests for page one of paged archive to main archive
      exit();
    }
    Cache::cacheServe(function() use ($page) { 
      global $query_context;
      $query_context = 'MainIndexArchive';
      (new ArchiveController())->getMainIndexArchive('blog', true, $page);
    });
  });
  
  // blog index (main index url)
  $router->get('/', function() {
    Cache::cacheServe(function() { 
      global $query_context;
      $query_context = 'MainIndexArchive';
      (new ArchiveController())->getMainIndexArchive('blog', true);
    });
  });
  
  /*
  *
  * singular routes
  *
  */
  
  // blog post
  $router->get('/posts/{slug}', function($slug) {
    Cache::cacheServe(function() use ($slug) { 
      global $query_context;
      $query_context = 'Single';
      (new SingleController('blog', $slug))->getSingle();
    });
  });
  
  /*
  *
  * taxonomy routes
  *
  */
  
  // blog categories (archive)
  $router->mount('/categories', function() use ($router) {
    
    // blog categories index paged (collection)
    $router->get('/page/{page}', function($page){
      // if ($page == 1) {
      //   header('Location: /blog/categories', true, 301);
      //   exit();
      // }
      // Rmcc\Cache::cacheServe(function() use ($page) { 
      //   $cat_collection = new Rmcc\CollectionArchiveController('blog', 'categories', true, $page);
      //   $cat_collection->getTaxCollectionArchive();
      // });
    });
    
    // blog categories index (collection)
    $router->get('/', function() {
      // Rmcc\Cache::cacheServe(function() { 
      //   $cat_collection = new Rmcc\CollectionArchiveController('blog', 'categories',   true);
      //   $cat_collection->getTaxCollectionArchive();
      // });
    });
  
    // blog categories term index (archive)
    $router->mount('/{term}', function() use ($router) {
    
      // blog categories term index paged
      $router->get('/page/{page}', function($term, $page){
        if ($page == 1) {
          header('Location: /blog/categories/'.$term, true, 301); // redirect requests for page one of paged archive to main archive
          exit();
        }
        Cache::cacheServe(function() use ($term, $page) { 
          global $query_type;
          $query_type = 'blog';
          global $query_context;
          $query_context = 'TaxTermArchive';
          (new ArchiveController())->getTaxTermArchive('blog', 'categories', $term, true, $page);
        });
      });
      
      // blog categories term index (archive)
      $router->get('/', function($term){
        Cache::cacheServe(function() use ($term) { 
          global $query_type;
          $query_type = 'blog';
          global $query_context;
          $query_context = 'TaxTermArchive';
          (new ArchiveController())->getTaxTermArchive('blog', 'categories', $term, true);
        });
      });
    
    });
  
  });
  
  // blog tags (archive)
  $router->mount('/tags', function() use ($router) {
    
    // blog tags index paged (collection)
    $router->get('/page/{page}', function($page){
      // if ($page == 1) {
      //   header('Location: /blog/tags', true, 301);
      //   exit();
      // }
      // Rmcc\Cache::cacheServe(function() use ($page) { 
      //   $tag_collection = new Rmcc\CollectionArchiveController('blog', 'tags', true, $page);
      //   $tag_collection->getTaxCollectionArchive();
      // });
    });
    
    // blog tags index (collection)
    $router->get('/', function() {
      // Rmcc\Cache::cacheServe(function() { 
      //   $tag_collection = new Rmcc\CollectionArchiveController('blog', 'tags', true);
      //   $tag_collection->getTaxCollectionArchive();
      // });
    });
  
    // blog tags term index (archive)
    $router->mount('/{term}', function() use ($router) {
    
      // blog tags term index paged
      $router->get('/page/{page}', function($term, $page){
        if ($page == 1) {
          header('Location: /blog/tags/'.$term, true, 301); // redirect requests for page one of paged archive to main archive
          exit();
        }
        Cache::cacheServe(function() use ($term, $page) {
          global $query_type;
          $query_type = 'blog';
          global $query_context;
          $query_context = 'TaxTermArchive';
          (new ArchiveController())->getTaxTermArchive('blog', 'tags', $term, true, $page);
        });
      });
      
      // blog tags term index (archive)
      $router->get('/', function($term){
        Cache::cacheServe(function() use ($term) { 
          global $query_type;
          $query_type = 'blog';
          global $query_context;
          $query_context = 'TaxTermArchive';
          (new ArchiveController())->getTaxTermArchive('blog', 'tags', $term, true);
        });
      });
    
    });
  
  });

});
// /*
// *
// * BLOG MAIN INDEX
// *
// */
// $router->get('/blog/', function() {
// 
//   global $query_context;
//   global $query_type;
//   $query_context = 'MainIndexArchive';
//   $query_type = 'blog';
// 
//   Cache::cacheServe(function() { 
//     (new ArchiveController())->getMainIndexArchive('blog', true);
//   });
// });
// /*
// *
// * BLOG MAIN INDEX - PAGED
// *
// */
// $router->get('/blog/page/{page}', function($page){
//  if ($page == 1) {
//    header('Location: /blog', true, 301); // redirect requests for page one of paged archive to main archive
//    exit();
//  }
//  Cache::cacheServe(function() use ($page) { 
//    global $query_type;
//    $query_type = 'blog';
//    global $query_context;
//    $query_context = 'MainIndexArchive';
//    (new ArchiveController())->getMainIndexArchive('blog', true, $page);
//  });
// });
// /*
// *
// * BLOG POSTS (SINGULAR)
// *
// */
// $router->get('/blog/posts/{slug}', function($slug) {
//   Cache::cacheServe(function() use ($slug) { 
//     global $query_type;
//     $query_type = 'blog';
//     global $query_context;
//     $query_context = 'Single';
//     (new SingleController('blog', $slug))->getSingle();
//   });
// });
// /*
// *
// * CATEGORIES
// *
// */
// // collection index
// // $router->get('/blog/categories/', function() {
// //   Cache::cacheServe(function() { 
// //     (new ArchiveController())->getTaxCollectionArchive('blog', 'categories', true);
// //   });
// // });
// // collection index - paged
// // $router->get('/blog/categories/page/{page}', function($page){
// //   if ($page == 1) {
// //     header('Location: /blog/categories', true, 301);
// //     exit();
// //   }
// //   Cache::cacheServe(function() use ($page) { 
// //     (new ArchiveController())->getTaxCollectionArchive('blog', 'categories', true, $page);
// //   });
// // });
// // term index - paged
// $router->get('/blog/categories/{term}/page/{page}', function($term, $page){
//  if ($page == 1) {
//    header('Location: /blog/categories/'.$term, true, 301); // redirect requests for page one of paged archive to main archive
//    exit();
//  }
//  Cache::cacheServe(function() use ($term, $page) { 
//    global $query_type;
//    $query_type = 'blog';
//    global $query_context;
//    $query_context = 'TaxTermArchive';
//    (new ArchiveController())->getTaxTermArchive('blog', 'categories', $term, true, $page);
//  });
// });
// // term index
// $router->get('/blog/categories/{term}/', function($term){
//   Cache::cacheServe(function() use ($term) { 
//     global $query_type;
//     $query_type = 'blog';
//     global $query_context;
//     $query_context = 'TaxTermArchive';
//     (new ArchiveController())->getTaxTermArchive('blog', 'categories', $term, true);
//   });
// });
// /*
// *
// * TAGS
// *
// */
// // collection index
// // $router->get('/blog/tags/', function() {
// //   Cache::cacheServe(function() { 
// //     (new ArchiveController())->getTaxCollectionArchive('blog', 'tags', true);
// //   });
// // });
// // collection index - paged
// // $router->get('/blog/tags/page/{page}', function($page){
// //   if ($page == 1) {
// //     header('Location: /blog/tags', true, 301);
// //     exit();
// //   }
// //   Cache::cacheServe(function() use ($page) { 
// //     (new ArchiveController())->getTaxCollectionArchive('blog', 'tags', true, $page);
// //   });
// // });
// // term index - paged
// $router->get('/blog/tags/{term}/page/{page}', function($term, $page){
//  if ($page == 1) {
//    header('Location: /blog/tags/'.$term, true, 301); // redirect requests for page one of paged archive to main archive
//    exit();
//  }
//  Cache::cacheServe(function() use ($term, $page) {
//    global $query_type;
//    $query_type = 'blog';
//    global $query_context;
//    $query_context = 'TaxTermArchive';
//    (new ArchiveController())->getTaxTermArchive('blog', 'tags', $term, true, $page);
//  });
// });
// // term index
// $router->get('/blog/tags/{term}/', function($term){
//   Cache::cacheServe(function() use ($term) { 
//     global $query_type;
//     $query_type = 'blog';
//     global $query_context;
//     $query_context = 'TaxTermArchive';
//     (new ArchiveController())->getTaxTermArchive('blog', 'tags', $term, true);
//   });
// });