<?php
namespace Rmcc;

global $config;

// foreach 'type'
foreach($config['types'] as $key => $value) {
  
  // create a MainIndexArchive route where $key = 'type' e.g: '/blog/'
  $router->get('/'.$key.'/', function() use ($key) {
    
    // parse the url to be checked for valid query parameters
    $params = parse_url($_SERVER['REQUEST_URI']);
    
    // if valid query params exist...
    if (isset($params['query']) && queryParamsExists($params['query'])) {
      
      // do a redirect for requests of '?p=1' to the main page as these are the same thing
      // parse_str($params['query'], $params_array);
      if($_SERVER['REQUEST_URI'] === '/'.$key.'?p=1' || $_SERVER['REQUEST_URI'] === '/'.$key.'/?p=1'){
        header('Location: /'.$key, true, 301);
        exit();
      }
      // do the queryMainIndexArchive route
      (new ArchiveController($key))->queryMainIndexArchive($params['query']);
      
    } 
    
    // if no valid query params
    else {
      // do the standard getMainIndexArchive route, Cached
      Cache::cacheServe(function() use ($key){ 
        (new ArchiveController($key))->getMainIndexArchive();
      });
    }
    
  });
  
  // create a MainIndexArchive route with $key, $items & the $slug (slug comes from the URL)
  $single = typeSettingByKey('key', $key , 'single'); // this gets the single item plural for a given 'type' e.g: 'posts' or 'projects'
  $router->get('/'.$key.'/'.$single.'/{slug}', function($slug) use ($key) {
    Cache::cacheServe(function() use ($key, $slug) { 
      (new SingleController($key, $slug))->getSingle();
    });
  });
  
  // foreach 'taxes_in_meta'
  foreach($value['taxes_in_meta'] as $tax) {
    
    // create a TaxTermArchive route with $key, $tax & the $term (term comes from the URL)
    $single_tax = taxSettingByKey($key, 'key', $tax , 'single'); // this gets the single item plural for a given 'tax' e.g: 'category' or 'location'
    $router->get('/'.$key.'/'.$single_tax.'/{term}/', function($term) use ($key, $tax){
      
      $params = parse_url($_SERVER['REQUEST_URI']);
      
      // if valid query params exist...
      if (isset($params['query']) && queryParamsExists($params['query'])) {
        
        // do a redirect for requests of '?p=1' to the main page as these are the same thing
        // parse_str($params['query'], $params_array);
        if($_SERVER['REQUEST_URI'] === '/'.$key.'/'.$tax.'/'.$term.'?p=1' || $_SERVER['REQUEST_URI'] === '/'.$key.'/'.$tax.'/'.$term.'/?p=1'){
          header('Location: /'.$key.'/'.$tax.'/'.$term, true, 301); // redirect requests for page one of paged archive to main archive
          exit();
        }
        // do the queryMainIndexArchive route
        (new ArchiveController($key))->queryTaxTermArchive($params['query'], $tax, $term);
        
      }
      
      // if no valid query params...
      else {
        // do the standard getTaxTermArchive route, Cached
        Cache::cacheServe(function() use ($key, $tax, $term) { 
          (new ArchiveController($key))->getTaxTermArchive($tax, $term);
        });
      } 
      
    });
    
    // create a TaxCollectionArchive route with $key & $tax
    $router->get('/'.$key.'/'.$tax.'/', function() use ($key, $tax) {
      
      $params = parse_url($_SERVER['REQUEST_URI']);
      
      // if valid query params exist...
      if (isset($params['query']) && queryParamsExists($params['query'])) {
        
        // do a redirect for requests of '?p=1' to the main page as these are the same thing
        // parse_str($params['query'], $params_array);
        if($_SERVER['REQUEST_URI'] === '/'.$key.'/'.$tax.'?p=1' || $_SERVER['REQUEST_URI'] === '/'.$key.'/'.$tax.'/?p=1'){
          header('Location: /'.$key.'/'.$tax, true, 301);
          exit();
        }
        // do the queryTaxCollectionArchive route
        (new ArchiveController($key))->queryTaxCollectionArchive($params['query'], $tax);
        
      }
      
      // if no valid query params...
      else {
        // do the standard getTaxCollectionArchive route, Cached
        Cache::cacheServe(function() use ($key, $tax){ 
          (new ArchiveController($key))->getTaxCollectionArchive($tax);
        });
      }
      
    });
    
  }
  
}