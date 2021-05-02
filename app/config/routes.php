<?php

// redirect to https if site protocol is set to https
if ($configs['site_protocol'] == "https") {
  if ($_SERVER['HTTPS'] != 'on') {
    $url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('Location: ' . $url, true, 301);
    exit();
  }
}

// new bramus router
$router = new \Bramus\Router\Router;
$router->setBasePath('/');

// homepage route
$router->get('/', function() {
  cache_serve(function() { 
    $homepage = new Single_controller;
    $homepage->index();
  });
});

// /blog
$router->mount(get_blog_route_base(), function() use ($router) {
  
  // blog index
  $router->get('/', function() {
    $blog = new Archive_controller();
    $blog->blog(null);
  });
  
  // blog index paged
  if($GLOBALS['configs']['is_blog_paged'] == true) {
    $router->get('/page/{page}', function($page){
     // redirect requests for page one of paged archive to main archive
     if ($page == 1) {
       header('Location: /blog', true, 301);
       exit();
     }
     $blog = new Archive_controller();
     $blog->blog($page);
    });
  }
  
  // blog post
  $router->get(get_post_route_base().'/{slug}', function($slug) {
    $post = new Single_controller;
    $post->post($slug);
  });
  
  // blog categories
  $router->mount(get_category_route_base(), function() use ($router) {
    
    // blog categories index paged
    if($GLOBALS['configs']['is_blog_paged'] == true) {
      $router->get('/page/{page}', function($page){
        if ($page == 1) {
          header('Location: '.get_blog_route_base().get_category_route_base(), true, 301);
          exit();
        }
        $category = new Archive_controller();
        $category->cat_collection($page);
      });
    }
  
    // blog categories index (collection)
    $router->get('/', function(){
      $category = new Archive_controller();
      $category->cat_collection(null);
    });

    // // blog categories term
    $router->mount('/{term}', function() use ($router) {
      
      // blog categories term index paged
      if($GLOBALS['configs']['is_blog_paged'] == true) {
        $router->get('/page/{page}', function($term, $page){
          // redirect requests for page one of paged archive to main archive
          if ($page == 1) {
            header('Location: '.get_blog_route_base().get_category_route_base().'/'.$term, true, 301);
            exit();
          }
          $category = new Archive_controller();
          $category->category($term, $page);
        });
      }
    
      // blog categories term index
      $router->get('/', function($term){
        $category = new Archive_controller();
        $category->category($term, null);
      });
    
    });
  
  });
  
  // blog tags
  $router->mount(get_tag_route_base(), function() use ($router) {
    
    // blog tags index paged
    if($GLOBALS['configs']['is_blog_paged'] == true) {
      $router->get('/page/{page}', function($page){
        if ($page == 1) {
          header('Location: '.get_blog_route_base().get_tag_route_base(), true, 301);
          exit();
        }
        $tag = new Archive_controller();
        $tag->tag_collection($page);
      });
    }
  
    // blog tags index (collection)
    $router->get('/', function(){
      $tag = new Archive_controller();
      $tag->tag_collection(null);
    });

    // // blog tags term
    $router->mount('/{term}', function() use ($router) {
      
      // blog tags term index paged
      if($GLOBALS['configs']['is_blog_paged'] == true) {
        $router->get('/page/{page}', function($term, $page){
          // redirect requests for page one of paged archive to main archive
          if ($page == 1) {
            header('Location: '.get_blog_route_base().get_tag_route_base().'/'.$term, true, 301);
            exit();
          }
          $tag = new Archive_controller();
          $tag->tag($term, $page);
        });
      }
    
      // blog tags term index
      $router->get('/', function($term){
        $tag = new Archive_controller();
        $tag->tag($term, null);
      });
    
    });
  
  });

});

// /portfolio
$router->mount(get_portfolio_route_base(), function() use ($router) {
  
  // portfolio index
  $router->get('/', function() {
    $portfolio = new Archive_controller();
    $portfolio->portfolio(null);
  });
  
  // portfolio index paged
  if($GLOBALS['configs']['is_portfolio_paged'] == true) {
    $router->get('/page/{page}', function($page){
     // redirect requests for page one of paged archive to main archive
     if ($page == 1) {
       header('Location: /portfolio', true, 301);
       exit();
     }
     $portfolio = new Archive_controller();
     $portfolio->portfolio($page);
    });
  }
  
  // portfolio project
  $router->get(get_project_route_base().'/{slug}', function($slug) {
    $project = new Single_controller;
    $project->project($slug);
  });

});

// page
$router->get('/{slug}', function($slug) {
  cache_serve(function() use ($slug) { 
    $page = new Single_controller;
    $page->page($slug, null);
  });
});

// errors
$router->set404(function() {
  header('HTTP/1.1 404 Not Found');
  $core = new Core_controller;
  $core->error();
});

// go
$router->run();