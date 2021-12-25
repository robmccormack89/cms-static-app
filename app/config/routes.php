<?php
namespace Rmcc;
use \Bramus\Router\Router as Router;

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

if ($config['enable_https']) {
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

// create the Homepage route
$router->get('/', function() {
  
  // parse the url, to be checked for valid query parameters
  $params = parse_url($_SERVER['REQUEST_URI']);
  
  // if valid query params exist, we will do a query of the Site
  if (isset($params['query']) && queryParamsExists($params['query'])) {
    
    // here we do a redirect for requests of '?p=1' to the main page as these are the same thing
    if($_SERVER['REQUEST_URI'] === '?p=1' || $_SERVER['REQUEST_URI'] === '/?p=1'){
      header('Location: /', true, 301);
      exit();
    }
    
    // then we do the Site query route
    (new ArchiveController())->querySite($params['query']);
    
  }
  
  // else, if no valid query params, we do a regular Single route for the Homepage
  else {
    
    // do the standard Single route for Homepage, with Cached
    Cache::cacheServe(function() { 
      (new SingleController('page', 'index'))->getSingle();
    });
    
  }
  
});

// contact form post route, for the Homepage! allows entering the contact form on homepage
$router->post('/', function() {
  (new SingleController('page', 'index'))->getContact();
});

/*
*
* ARCHIVED ROUTES (content_types defined in $config['types'])
*
*/

include('routes.archived.php');

/*
*
* PAGES ROUTES
*
*/

// contact form post route, contact page!  allows entering the contact form on contact page
$router->post('/contact', function() {
  (new SingleController('page', 'contact'))->getContact();
});

$router->get('/{slug}', function($slug) {
  Cache::cacheServe(function() use ($slug) { 
    (new SingleController('page', $slug))->getSingle();
  });
});


// 404 error route. in most cases 404 errors will be rendered rather than routed, see CoreController->error()
// $router->set404(function() {
//   header('HTTP/1.1 404 Not Found');
//   echo '<hr>Error 404';
// });

// go
$router->run();