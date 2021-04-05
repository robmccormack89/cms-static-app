<?php
// redirect to https if site protocol is set to https
if ($configs['site_protocol'] == "https") {
  if ($_SERVER['HTTPS'] != 'on') {
    $url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('Location: ' . $url, true, 301);
    exit();
  }
}
// include the router class. see https://phprouter.com/ for further route setups & information
require_once('router.php');

// homepage route
$router->get('/', function(){
  $Home_controller = new Home_controller;
  $Home_controller->index();
});

// dynamic pages route
$router->get('/:slug', function($slug){
  $Pages_controller = new Pages_controller;
  $Pages_controller->any($slug);
});

// error page route (goes last)
$router->any('/404', function(){
  $Core_controller = new Core_controller;
  $Core_controller->error();
});