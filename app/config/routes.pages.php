<?php
namespace Rmcc; // set the Rmcc namespace for using Rmcc classes

/*
*
* PAGES
*
*/

$router->post('/contact', function() {
  Cache::cacheServe(function() { 
    (new SingleController('page', 'contact'))->getSingle();
  });
});

$router->get('/{slug}', function($slug) {
  Cache::cacheServe(function() use ($slug) { 
    (new SingleController('page', $slug))->getSingle();
  });
});