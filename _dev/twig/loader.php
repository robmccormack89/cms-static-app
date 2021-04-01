<?php
// Load twig
require_once __DIR__.'/vendor/autoload.php';

// setup the views directories
$viewsDirs = array(
  'views/',
  'views/parts',
);
// Specify our Twig templates location
$loader = new \Twig\Loader\FilesystemLoader($viewsDirs);

// make some variables
$SiteTitle = 'Your Site Title';
$SomeOtherVariable = 'Lorem Ipsum Dolor';

// make some data arrays

// simple array
$cars = array("Volvo", "BMW", "Toyota");
// simple array with key => value pairs
$fruits = array(
  "fruit1" => "apple",
  "fruit2" => "orange",
);
// nested array (like an array of post objects)
$movies = array(
  array(
    "title" => "Rear Window",
    "director" => "Alfred Hitchcock",
    "year" => 1954
  ),
  array(
    "title" => "Full Metal Jacket",
    "director" => "Stanley Kubrick",
    "year" => 1987
  ),
  array(
    "title" => "Mean Streets",
    "director" => "Martin Scorsese",
    "year" => 1973
  )
);
// using json data
$jsonobj = '{"Peter":35,"Ben":37,"Joe":43}';
// var_dump(json_decode($jsonobj));

// Instantiate our Twig
$twig = new \Twig\Environment($loader);

// add our variables to the context
$twig->addGlobal('SiteTitle', $SiteTitle);
$twig->addGlobal('SomeOtherVariable', $SomeOtherVariable);
$twig->addGlobal('cars', $cars);
$twig->addGlobal('fruits', $fruits);
$twig->addGlobal('movies', $movies);

// set up simple url routing
$request = $_SERVER['REQUEST_URI'];

switch ($request) {
  case '/' :
  case '' :
    require __DIR__ . '/controllers/index.php';
    break;
  case '/about' :
    require __DIR__ . '/controllers/about.php';
    break;
  default:
    http_response_code(404);
    require __DIR__ . '/static/404.php';
    break;
}