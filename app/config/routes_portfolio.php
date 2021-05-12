<?php

// /portfolio
$router->mount(get_portfolio_route_base(), function() use ($router) {
  
  // portfolio index
  $router->get('/', function() {
    cache_serve(function() { 
      $portfolio = new Archive_controller();
      $portfolio->portfolio(null);
    });
  });
  
  // portfolio index paged
  if($GLOBALS['configs']['is_portfolio_paged'] == true) {
    $router->get('/page/{page}', function($page){
     // redirect requests for page one of paged archive to main archive
     if ($page == 1) {
       header('Location: /portfolio', true, 301);
       exit();
     }
     cache_serve(function() use ($page) { 
       $portfolio = new Archive_controller();
       $portfolio->portfolio($page);
     });
    });
  }
  
  // portfolio single project
  $router->get(get_project_route_base().'/{slug}', function($slug) {
    cache_serve(function() use ($slug) { 
      $project = new Single_controller;
      $project->project($slug);
    });
  });

});