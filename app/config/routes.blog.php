<?php
namespace Rmcc;

$router->get('/blog/', function() {
  $params = parse_url($_SERVER['REQUEST_URI']);
  if (isset($params['query']) && queryParamsExists($params['query'])) {
    parse_str($params['query'], $params_array);
    if($_SERVER['REQUEST_URI'] === '/blog?p=1' || $_SERVER['REQUEST_URI'] === '/blog/?p=1'){
      header('Location: /blog', true, 301);
      exit();
    }
    (new ArchiveController('blog'))->queryMainIndexArchive($params['query']);
  } else {
    Cache::cacheServe(function(){ 
      (new ArchiveController('blog'))->getMainIndexArchive();
    });
  }
});

$router->get('/blog/posts/{slug}', function($slug) {
  Cache::cacheServe(function() use ($slug) { 
    (new SingleController('blog', $slug))->getSingle();
  });
});

$router->get('/blog/categories/{term}/', function($term){
   $params = parse_url($_SERVER['REQUEST_URI']);
   if (isset($params['query']) && queryParamsExists($params['query'])) {
     parse_str($params['query'], $params_array);
     if($_SERVER['REQUEST_URI'] === '/blog/categories/'.$term.'?p=1' || $_SERVER['REQUEST_URI'] === '/blog/categories/'.$term.'/?p=1'){
       header('Location: /blog/categories/'.$term, true, 301); // redirect requests for page one of paged archive to main archive
       exit();
     }
     (new ArchiveController('blog'))->queryTaxTermArchive($params['query'], 'categories', $term);
   } else {
     Cache::cacheServe(function() use ($term) { 
       (new ArchiveController('blog'))->getTaxTermArchive('categories', $term);
     });
   } 
});

$router->get('/blog/tags/{term}/', function($term){
   $params = parse_url($_SERVER['REQUEST_URI']);
   if (isset($params['query']) && queryParamsExists($params['query'])) {
     parse_str($params['query'], $params_array);
     if($_SERVER['REQUEST_URI'] === '/blog/tags/'.$term.'?p=1' || $_SERVER['REQUEST_URI'] === '/blog/tags/'.$term.'/?p=1'){
       header('Location: /blog/tags/'.$term, true, 301); // redirect requests for page one of paged archive to main archive
       exit();
     }
     (new ArchiveController('blog'))->queryTaxTermArchive($params['query'], 'tags', $term);
   } else {
     Cache::cacheServe(function() use ($term) { 
       // print_r('hey');
       (new ArchiveController('blog'))->getTaxTermArchive('tags', $term);
     });
   } 
});

$router->get('/blog/categories/', function() {
  $params = parse_url($_SERVER['REQUEST_URI']);
  if (isset($params['query']) && queryParamsExists($params['query'])) {
    parse_str($params['query'], $params_array);
    if($_SERVER['REQUEST_URI'] === '/blog/categories?p=1' || $_SERVER['REQUEST_URI'] === '/blog/categories/?p=1'){
      header('Location: /blog/categories', true, 301);
      exit();
    }
    (new ArchiveController('blog'))->queryTaxCollectionArchive($params['query'], 'categories');
  } else {
    Cache::cacheServe(function(){ 
      (new ArchiveController('blog'))->getTaxCollectionArchive('categories');
    });
  }
});

$router->get('/blog/tags/', function() {
  $params = parse_url($_SERVER['REQUEST_URI']);
  if (isset($params['query']) && queryParamsExists($params['query'])) {
    parse_str($params['query'], $params_array);
    if($_SERVER['REQUEST_URI'] === '/blog/tags?p=1' || $_SERVER['REQUEST_URI'] === '/blog/tags/?p=1'){
      header('Location: /blog/tags', true, 301);
      exit();
    }
    (new ArchiveController('blog'))->queryTaxCollectionArchive($params['query'], 'tags');
  } else {
    Cache::cacheServe(function(){ 
      (new ArchiveController('blog'))->getTaxCollectionArchive('tags');
    });
  }
});