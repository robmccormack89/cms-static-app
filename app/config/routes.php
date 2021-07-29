<?php
namespace Rmcc; // set the Rmcc namespace for using Rmcc classes
use \Bramus\Router\Router as Router; // use the Bramus Router class as Router, initialize it & set the base path

/*
*
* SETUP
*
*/

$router = new Router;
$router->setBasePath('/');

/*
*
* HTTPS REDIRECT (IF ENABLED)
*
*/

if ($config['site_protocol'] == "https") {
  if ($_SERVER['HTTPS'] != 'on') {
    $url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('Location: ' . $url, true, 301);
    exit();
  }
}

/*
*
* HOMEPAGE
*
*/

$router->get('/', function() {
  
  $params = parse_url($_SERVER['REQUEST_URI']);
  if (isset($params['query']) && queryParamsExists($params['query'])) {
    parse_str($params['query'], $params_array);
    if($_SERVER['REQUEST_URI'] === '?p=1' || $_SERVER['REQUEST_URI'] === '/?p=1'){
      header('Location: /', true, 301);
      exit();
    }
    (new ArchiveController())->querySite($params['query']);
  } else {
    Cache::cacheServe(function() { 
      (new SingleController('page', 'index'))->getSingle();
    });
  }
  
});

/*
*
* BLOG ROUTES
*
*/

include('routes.blog.php');

/*
*
* PORTFOLIO ROUTES
*
*/

include('routes.portfolio.php');

/*
*
* PAGES ROUTES
*
*/

include('routes.pages.php');


// 404 error route. in most cases 404 errors will be rendered rather than routed, see CoreController->error()
$router->set404(function() {
  header('HTTP/1.1 404 Not Found');
  echo '<hr>Error 404';
});

// go
$router->run();