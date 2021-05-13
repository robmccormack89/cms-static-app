<?php
// redirect to https if site protocol is set to https
if ($config['site_protocol'] == "https") {
  if ($_SERVER['HTTPS'] != 'on') {
    $url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('Location: ' . $url, true, 301);
    exit();
  }
}

// new bramus router
$router = new \Bramus\Router\Router;
$router->setBasePath('/');

// homepage route
$router->get('/', function() {
  $query_params = $_SERVER['QUERY_STRING'];
  if ($query_params) {
    echo $query_params;
  } else {
    Rmcc\Cache::cacheServe(function() { 
      $homepage = new Rmcc\SingleController('page', 'page', 'index');
      $homepage->getSingle();
    });
  }
});

include('routes_blog.php');

// /page
$router->get('/{slug}', function($slug) {
  Rmcc\Cache::cacheServe(function() use ($slug) { 
    $page = new Rmcc\SingleController('page', 'page', $slug);
    $page->getSingle();
  });
});

// error pages 404
$router->set404(function() {
  header('HTTP/1.1 404 Not Found');
  echo '<hr>Error 404';
});

// go
$router->run();