<?php

// /portfolio
$router->mount('/portfolio', function() use ($router) {
  
  // portfolio index paged (archive)
  $router->get('/page/{page}', function($page){
   if ($page == 1) {
     header('Location: /blog', true, 301); // redirect requests for page one of paged archive to main archive
     exit();
   }
   Rmcc\Cache::cacheServe(function() use ($page) { 
     $project = new Rmcc\ArchiveController();
     $project->getMainIndexArchive(
       'portfolio',
       'projects',
       null,
       true,
       $page
     );
   });
  });
  
  // portfolio index (archive)
  $router->get('/', function() {
    Rmcc\Cache::cacheServe(function() { 
      $project = new Rmcc\ArchiveController;
      $project->getMainIndexArchive(
        'portfolio',
        'projects',
        null,
        true
      );
    });
  });
  
  // portfolio single project (single)
  $router->get('/projects/{slug}', function($slug) {
    Rmcc\Cache::cacheServe(function() use ($slug) { 
      $project = new Rmcc\SingleController();
      $project->getSingle('project', 'portfolio', $slug, 'projects');
    });
  });

});