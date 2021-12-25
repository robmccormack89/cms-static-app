<?php
namespace Rmcc;

/**
 *
 * This file creates routes based on $config['types'] & respective taxonomies that have been added to config.php
 * Types must be added to config as well as have valid data in json, otherwise 404 error (should produce 404 anyways)
 * 
 *
 * ROUTE TYPES:
 * 
 * MainIndexArchive (cached) - '/blog'
 * queryMainIndexArchive - '/blog?per_page=1&p=2'
 * 
 * TaxTermArchive (cached) - '/blog/category/news'
 * queryTaxTermArchive - '/blog/category/news?per_page=1&p=2'
 *
 * TaxCollectionArchive (cached) - '/blog/categories'
 * queryTaxCollectionArchive - '/blog/categories?show_all'
 * 
 * Single (cached) - '/blog/post/this-is-a-post'
 *
 */

global $config;

// foreach 'type' in config 'types' where $key = 'type' e.g: 'blog'
foreach($config['types'] as $key => $value) {
  
  // check to see if given type is publically visible
  if($config['types'][$key]['visibility'] == 'public'){
    
    // create a MainIndexArchive route
    $router->get('/'.$key.'/', function() use ($key) {
      
      // parse the url, to be checked for valid query parameters
      $params = parse_url($_SERVER['REQUEST_URI']);
      
      // if valid query params exist, we will do a query of the MainIndexArchive
      if (isset($params['query']) && queryParamsExists($params['query'])) {
        
        // here we do a redirect for requests of '?p=1' to the main page as these are the same thing
        if($_SERVER['REQUEST_URI'] === '/'.$key.'?p=1' || $_SERVER['REQUEST_URI'] === '/'.$key.'/?p=1'){
          header('Location: /'.$key, true, 301);
          exit();
        }
        
        // then we do the MainIndexArchive query route
        (new ArchiveController())->queryMainIndexArchive($key, $params['query']);
        
      } 
      
      // else, if no valid query params, we do a regular MainIndexArchive
      else {
        
        // do the standard getMainIndexArchive route, with Cached
        Cache::cacheServe(function() use ($key){ 
          (new ArchiveController())->getMainIndexArchive($key);
        });
        
      }
      
    });
    
    // create a Single route with $key, $items & $slug (slug comes from the URL)
    $single = typeSettingByKey('key', $key , 'single'); // this gets the single item plural for a given 'type' e.g: 'posts' or 'projects' from 'blog' or 'portfolio'
    
    $router->get('/'.$key.'/'.$single.'/{slug}', function($slug) use ($key) {
      
      // do the getSingle route, with Cached
      Cache::cacheServe(function() use ($key, $slug) { 
        (new SingleController($key, $slug))->getSingle();
      });
      
    });
    
    // foreach 'taxes_in_meta'
    foreach($value['taxes_in_meta'] as $tax) {
      
      // create a TaxTermArchive route with $key, $tax & $term (term comes from the URL)
      $single_tax = taxSettingByKey($key, 'key', $tax , 'single'); // this gets the plural for a given taxonomy e.g: 'category' or 'location' from 'categories' or 'locations'
      $router->get('/'.$key.'/'.$single_tax.'/{term}/', function($term) use ($key, $tax){
        
        $params = parse_url($_SERVER['REQUEST_URI']);
        
        // if valid query params exist, we will do a query of the MainIndexArchive
        if (isset($params['query']) && queryParamsExists($params['query'])) {
          
          // here we do a redirect for requests of '?p=1' to the main page as these are the same thing
          if($_SERVER['REQUEST_URI'] === '/'.$key.'/'.$tax.'/'.$term.'?p=1' || $_SERVER['REQUEST_URI'] === '/'.$key.'/'.$tax.'/'.$term.'/?p=1'){
            header('Location: /'.$key.'/'.$tax.'/'.$term, true, 301); // redirect requests for page one of paged archive to main archive
            exit();
          }
          
          // then we do the TaxTermArchive query route
          (new ArchiveController())->queryTaxTermArchive($key, $tax, $term, $params['query']);
          
        }
        
        // else, if no valid query params, we do a regular TaxTermArchive
        else {
          
          // do the standard TaxTermArchive route, with Cached
          Cache::cacheServe(function() use ($key, $tax, $term) { 
            (new ArchiveController())->getTaxTermArchive($key, $tax, $term);
          });
          
        } 
        
      });
      
      // create a TaxCollectionArchive route with $key & $tax
      $router->get('/'.$key.'/'.$tax.'/', function() use ($key, $tax) {
        
        $params = parse_url($_SERVER['REQUEST_URI']);
        
        // if valid query params exist, we will do a query of the TaxCollectionArchive
        if (isset($params['query']) && queryParamsExists($params['query'])) {
          
          // here we do a redirect for requests of '?p=1' to the main page as these are the same thing
          if($_SERVER['REQUEST_URI'] === '/'.$key.'/'.$tax.'?p=1' || $_SERVER['REQUEST_URI'] === '/'.$key.'/'.$tax.'/?p=1'){
            header('Location: /'.$key.'/'.$tax, true, 301);
            exit();
          }
          
          // then we do the TaxCollectionArchive query route
          (new ArchiveController())->queryTaxCollectionArchive($key, $tax, $params['query']);
          
        }
        
        // else, if no valid query params, we do a regular TaxTermArchive
        else {
          
          // do the standard TaxCollectionArchive route, with Cached
          Cache::cacheServe(function() use ($key, $tax){ 
            (new ArchiveController())->getTaxCollectionArchive($key, $tax);
          });
          
        }
        
      });
      
    }
    
  }
  
}