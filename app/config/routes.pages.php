<?php
namespace Rmcc; // set the Rmcc namespace for using Rmcc classes

/*
*
* PAGES
*
*/

$router->get('/{slug}', function($slug) {
  Cache::cacheServe(function() use ($slug) { 
    (new SingleController('page', $slug))->getSingle();
  });
});