<?php
namespace Rmcc; // set the Rmcc namespace for using Rmcc classes

/*
*
* PAGES
*
*/

$router->post('/{slug}', function($slug) {
  Cache::cacheServe(function() use ($slug) { 
    (new SingleController('page', $slug))->getContact();
  });
});

$router->get('/{slug}', function($slug) {
  Cache::cacheServe(function() use ($slug) { 
    (new SingleController('page', $slug))->getSingle();
  });
});