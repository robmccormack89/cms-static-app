<?php
namespace Rmcc; // set the Rmcc namespace for using Rmcc classes

/*
*
* HOMEPAGE
*
*/

$router->get('/', function() {
  Cache::cacheServe(function() { 
    (new SingleController('page', 'index'))->getSingle();
  });
});