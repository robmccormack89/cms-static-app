<?php

// for routing for new types, copy this file into config folder & rename according to new type-key. e.g routes_portfolio.php
// then search/replace the old type-key(blog) in this file with your new type-key e.g portfolio
// data for the content type must exist in json file firstly
// config.php -> type_settings must contain item for your new content type secondly
// dont forget to load the new routes up in routes.php at the end.

// Strings to replace: 'blog', 'post/posts'
// If taxonomies: 'categories', 'tags'

/*
*
* The blog routes. with index_uri = /blog
*
*/

$router->mount('/blog', function() use ($router) {
  
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
   Rmcc\Cache::cacheServe(function() use ($page) { 
     $blog = new Rmcc\ArchiveController('blog', true, $page);
     $blog->getMainIndexArchive();
   });
  });
  
  // blog index (main index url)
  $router->get('/', function() {
    Rmcc\Cache::cacheServe(function() { 
      $blog = new Rmcc\ArchiveController('blog', true);
      $blog->getMainIndexArchive();
    });
  });
  
  /*
  *
  * singular routes
  *
  */
  
  // blog post
  $router->get('/posts/{slug}', function($slug) {
    Rmcc\Cache::cacheServe(function() use ($slug) { 
      $post = new Rmcc\SingleController('blog', $slug);
      $post->getSingle();
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
      if ($page == 1) {
        header('Location: /blog/categories', true, 301);
        exit();
      }
      Rmcc\Cache::cacheServe(function() use ($page) { 
        $cat_collection = new Rmcc\CollectionArchiveController('blog', 'categories', true, $page);
        $cat_collection->getTaxCollectionArchive();
      });
    });
    
    // blog categories index (collection)
    $router->get('/', function() {
      Rmcc\Cache::cacheServe(function() { 
        $cat_collection = new Rmcc\CollectionArchiveController('blog', 'categories',   true);
        $cat_collection->getTaxCollectionArchive();
      });
    });
  
    // blog categories term index (archive)
    $router->mount('/{term}', function() use ($router) {
    
      // blog categories term index paged
      $router->get('/page/{page}', function($term, $page){
       if ($page == 1) {
         header('Location: /blog/categories/'.$term, true, 301); // redirect requests for page one of paged archive to main archive
         exit();
       }
       Rmcc\Cache::cacheServe(function() use ($term, $page) { 
         $category = new Rmcc\TermArchiveController('blog', 'categories', $term, true, $page);
         $category->getTaxTermArchive();
       });
      });
      
      // blog categories term index (archive)
      $router->get('/', function($term){
        Rmcc\Cache::cacheServe(function() use ($term) { 
          $category = new Rmcc\TermArchiveController('blog', 'categories', $term, true);
          $category->getTaxTermArchive();
        });
      });
    
    });
  
  });
  
  // blog tags (archive)
  $router->mount('/tags', function() use ($router) {
    
    // blog tags index paged (collection)
    $router->get('/page/{page}', function($page){
      if ($page == 1) {
        header('Location: /blog/tags', true, 301);
        exit();
      }
      Rmcc\Cache::cacheServe(function() use ($page) { 
        $tag_collection = new Rmcc\CollectionArchiveController('blog', 'tags', true, $page);
        $tag_collection->getTaxCollectionArchive();
      });
    });
    
    // blog tags index (collection)
    $router->get('/', function() {
      Rmcc\Cache::cacheServe(function() { 
        $tag_collection = new Rmcc\CollectionArchiveController('blog', 'tags', true);
        $tag_collection->getTaxCollectionArchive();
      });
    });
  
    // blog tags term index (archive)
    $router->mount('/{term}', function() use ($router) {
    
      // blog tags term index paged
      $router->get('/page/{page}', function($term, $page){
       if ($page == 1) {
         header('Location: /blog/tags/'.$term, true, 301); // redirect requests for page one of paged archive to main archive
         exit();
       }
       Rmcc\Cache::cacheServe(function() use ($term, $page) { 
         $tag = new Rmcc\TermArchiveController('blog', 'tags', $term, true, $page);
         $tag->getTaxTermArchive();
       });
      });
      
      // blog tags term index (archive)
      $router->get('/', function($term){
        Rmcc\Cache::cacheServe(function() use ($term) { 
          $tag = new Rmcc\TermArchiveController('blog', 'tags', $term, true);
          $tag->getTaxTermArchive();
        });
      });
    
    });
  
  });

});