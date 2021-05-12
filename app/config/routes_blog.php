<?php

// /blog
$router->mount('/blog', function() use ($router) {
  
  // blog index
  $router->get('/', function() {
    Rmcc\Cache::cacheServe(function() { 
      $blog = new Rmcc\ArchiveController();
      $blog->blog();
    });
  });
  
  // blog index paged
  // if($GLOBALS['config']['is_blog_paged'] == true) {
  //   $router->get('/page/{page}', function($page){
  //    // redirect requests for page one of paged archive to main archive
  //    if ($page == 1) {
  //      header('Location: /blog', true, 301);
  //      exit();
  //    }
  //    cacheServe(function() use ($page) { 
  //      $blog = new ArchiveController();
  //      $blog->blog($page);
  //    });
  //   });
  // }
  
  // blog single post
  $router->get('/posts/{slug}', function($slug) {
    Rmcc\Cache::cacheServe(function() use ($slug) { 
      $post = new Rmcc\SingleController;
      $post->post($slug);
    });
  });
  
  // blog categories
  // $router->mount(get_category_route_base(), function() use ($router) {
  // 
  //   // blog categories index paged (collection)
  //   if($GLOBALS['configs']['is_blog_paged'] == true) {
  //     $router->get('/page/{page}', function($page){
  //       if ($page == 1) {
  //         header('Location: '.get_blog_route_base().get_category_route_base(), true, 301);
  //         exit();
  //       }
  //       cacheServe(function() use ($page) { 
  //         $category = new ArchiveController();
  //         $category->cat_collection($page);
  //       });
  //     });
  //   }
  // 
  //   // blog categories index (collection)
  //   $router->get('/', function(){
  //     cacheServe(function() { 
  //       $category = new ArchiveController();
  //       $category->cat_collection(null);
  //     });
  //   });
  // 
  //   // blog categories term index
  //   $router->mount('/{term}', function() use ($router) {
  // 
  //     // blog categories term index paged
  //     if($GLOBALS['configs']['is_blog_paged'] == true) {
  //       $router->get('/page/{page}', function($term, $page){
  //         // redirect requests for page one of paged archive to main archive
  //         if ($page == 1) {
  //           header('Location: '.get_blog_route_base().get_category_route_base().'/'.$term, true, 301);
  //           exit();
  //         }
  //         cacheServe(function() use ($term, $page) { 
  //           $category = new ArchiveController();
  //           $category->category($term, $page);
  //         });
  //       });
  //     }
  // 
  //     // blog categories term index
  //     $router->get('/', function($term){
  //       cacheServe(function() use ($term) { 
  //         $category = new ArchiveController();
  //         $category->category($term, null);
  //       });
  //     });
  // 
  //   });
  // 
  // });
  
  // blog tags
  // $router->mount(get_tag_route_base(), function() use ($router) {
  // 
  //   // blog tags index paged (collection)
  //   if($GLOBALS['configs']['is_blog_paged'] == true) {
  //     $router->get('/page/{page}', function($page){
  //       if ($page == 1) {
  //         header('Location: '.get_blog_route_base().get_tag_route_base(), true, 301);
  //         exit();
  //       }
  //       cacheServe(function() use ($page) { 
  //         $tag = new ArchiveController();
  //         $tag->tag_collection($page);
  //       });
  //     });
  //   }
  // 
  //   // blog tags index (collection)
  //   $router->get('/', function(){
  //     cacheServe(function() { 
  //       $tag = new ArchiveController();
  //       $tag->tag_collection(null);
  //     });
  //   });
  // 
  //   // // blog tags term index
  //   $router->mount('/{term}', function() use ($router) {
  // 
  //     // blog tags term index paged
  //     if($GLOBALS['configs']['is_blog_paged'] == true) {
  //       $router->get('/page/{page}', function($term, $page){
  //         // redirect requests for page one of paged archive to main archive
  //         if ($page == 1) {
  //           header('Location: '.get_blog_route_base().get_tag_route_base().'/'.$term, true, 301);
  //           exit();
  //         }
  //         cacheServe(function() use ($term, $page) { 
  //           $tag = new ArchiveController();
  //           $tag->tag($term, $page);
  //         });
  //       });
  //     }
  // 
  //     // blog tags term index
  //     $router->get('/', function($term){
  //       cacheServe(function() use ($term) { 
  //         $tag = new ArchiveController();
  //         $tag->tag($term, null);
  //       });
  //     });
  // 
  //   });
  // 
  // });

});