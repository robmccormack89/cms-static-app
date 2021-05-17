<?php

// /blog
$router->mount('/blog', function() use ($router) {
  
  // blog index paged (archive)
  $router->get('/page/{page}', function($page){
   if ($page == 1) {
     header('Location: /blog', true, 301); // redirect requests for page one of paged archive to main archive
     exit();
   }
   Rmcc\Cache::cacheServe(function() use ($page) { 
     $blog = new Rmcc\ArchiveController();
     $blog->getMainIndexArchive(
       'blog',
       'posts',
       array('categories', 'tags'),
       true,
       $page // paged value. 2, 3, or 4 etc
     );
   });
  });
  
  // blog index (archive)
  $router->get('/', function() {
    Rmcc\Cache::cacheServe(function() { 
      $blog = new Rmcc\ArchiveController;
      $blog->getMainIndexArchive(
        'blog', // content type key e.g blog or portfolio
        'posts', // content type items key e.g posts or projects
        array('categories', 'tags'), // taxonomies to include meta for in the listings
        true // is archive paged. true or false
      );
    });
  });
  
  // blog single post (single)
  $router->get('/posts/{slug}', function($slug) {
    Rmcc\Cache::cacheServe(function() use ($slug) { 
      $post = new Rmcc\SingleController();
      $post->getSingle('post', 'blog', $slug, 'posts');
    });
  });
  
  // blog categories (archive)
  $router->mount('/categories', function() use ($router) {
    
    // blog categories index paged (collection)
    $router->get('/page/{page}', function($page){
      if ($page == 1) {
        header('Location: /blog/categories', true, 301);
        exit();
      }
      Rmcc\Cache::cacheServe(function() use ($page) { 
        $cat_collection = new Rmcc\CollectionArchiveController;
        $cat_collection->getTaxCollectionArchive(
          'blog', // content type key e.g blog or portfolio
          'categories', // taxonomy key
          true, // is archive paged. true or false
          $page
        );
      });
    });
    
    // blog categories index (collection)
    $router->get('/', function() {
      Rmcc\Cache::cacheServe(function() { 
        $cat_collection = new Rmcc\CollectionArchiveController;
        $cat_collection->getTaxCollectionArchive(
          'blog', // content type key e.g blog or portfolio
          'categories', // taxonomy key
          true // is archive paged. true or false
        );
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
         $category = new Rmcc\TermArchiveController();
         $category->getTaxTermArchive(
           'blog',
           'posts',
           'categories', // taxonomy key
           $term, // taxonomy term slug
           array('categories', 'tags'),
           true,
           $page
         );
       });
      });
      
      // blog categories term index (archive)
      $router->get('/', function($term){
        Rmcc\Cache::cacheServe(function() use ($term) { 
          $category = new Rmcc\TermArchiveController;
          $category->getTaxTermArchive(
            'blog', // content type key
            'posts', // items key
            'categories', // taxonomy key
            $term, // taxonomy term slug
            array('categories', 'tags'), // taxonomies to include meta for in the listings
            true
          );
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
        $tag_collection = new Rmcc\CollectionArchiveController;
        $tag_collection->getTaxCollectionArchive(
          'blog', // content type key e.g blog or portfolio
          'tags', // taxonomy key
          true, // is archive paged. true or false
          $page
        );
      });
    });
    
    // blog tags index (collection)
    $router->get('/', function() {
      Rmcc\Cache::cacheServe(function() { 
        $tag_collection = new Rmcc\CollectionArchiveController;
        $tag_collection->getTaxCollectionArchive(
          'blog', // content type key e.g blog or portfolio
          'tags', // taxonomy key
          true // is archive paged. true or false
        );
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
         $tag = new Rmcc\TermArchiveController();
         $tag->getTaxTermArchive(
           'blog',
           'posts',
           'tags', // taxonomy key
           $term, // taxonomy term slug
           array('categories', 'tags'),
           true,
           $page
         );
       });
      });
      
      // blog tags term index (archive)
      $router->get('/', function($term){
        Rmcc\Cache::cacheServe(function() use ($term) { 
          $tag = new Rmcc\TermArchiveController;
          $tag->getTaxTermArchive(
            'blog', // content type key
            'posts', // items key
            'tags', // taxonomy key
            $term, // taxonomy term slug
            array('categories', 'tags'), // taxonomies to include meta for in the listings
            true
          );
        });
      });
    
    });
  
  });

});