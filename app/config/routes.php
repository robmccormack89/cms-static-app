<?php
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
* HOMEPAGE ROUTE/s
*
*/

include('routes/_home.php');

/*
*
* BLOG ROUTES
*
*/

include('routes/_blog.php');

/*
*
* PORTFOLIO ROUTES
*
*/

include('routes/_portfolio.php');

/*
*
* PAGES ROUTES
*
*/

include('routes/_pages.php');


// 404 error route. in most cases 404 errors will be rendered rather than routed, see CoreController->error()
$router->set404(function() {
  header('HTTP/1.1 404 Not Found');
  echo '<hr>Error 404';
});

// go
$router->run();