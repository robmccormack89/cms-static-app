<?php

require_once('router.php');

// homepage
$router->get('/', function(){
  $GLOBALS['Home_controller']->index();
});

// about page
// $router->get('/about', function(){
//   require_once($_SERVER['DOCUMENT_ROOT'] . '/app/core/Core_controller.php');
//   $Core_controller->about();
// });

// dynamic page
$router->get('/:slug', function($slug){
  $GLOBALS['Core_controller']->any($slug);
});

// error (goes last)
$router->any('/404', function(){
  // echo $GLOBALS['twig']->render('404.twig');
  $GLOBALS['Core_controller']->error();
});

// Dynamic GET. Example with 1 variable
// In the URL -> http://localhost/user/111
// The output -> The id passed is: 111
$router->get('/user/:name', function($name){
echo "The name passed is: $name ";
});

// Dynamic GET. Example with 2 variables
// In the URL -> http://localhost/user/A/B
// The output -> The full name is A B
get('/user/:name/:last_name', function($name, $last_name){
echo "The full name is: $name $last_name ";
});

// Dynamic GET. Example with 2 variables with static
// In the URL -> http://localhost/product/shoes/color/blue
// The output -> The product is shoes with color blue
get('/product/:type/color/:color', function($type, $color){
echo "The product is $type with color $color";
});

// Dynamic GET. Example with 1 variable and 1 query string
// In the URL -> http://localhost/item/car?price=10
// The output -> The item name is car ane the price is 10
get('/item/:name', function($name){
echo "The item name is $name ane the price is {$_GET['price']}";
});

// Dynamic GET. Example with 2 variables and 2 query strings
// In the URL -> http://localhost/item/Table/20?weight=10&height=30
// The output -> Name: Table Price: 20 Weight: 10 Height: 30
get('/item/:name/:price', function($name, $price){
echo "Name: $name Price: $price Weight: {$_GET['weight']} Height: {$_GET['height']}";
});

// ##################################################
// ##################################################
// ##################################################
// For post the exact same rules as get applied.
// Look at the examples above.

// Static POST
// In the form  -> <input type="text" name="user_name" value="John">
// The output   -> User created with name: John
post('/users', function(){
echo "User created with name: {$_POST['user_name']}";
});

// ##################################################
// ##################################################
// ##################################################
// any can be used for GETs or POSTs

// For GET or POST
any('/404', function(){
echo 'Not found';
});