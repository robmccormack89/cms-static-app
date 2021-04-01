<?php

$routes->get('/about', function(){
  require_once($GLOBALS['twigger']);
  
  $context['page'] = array(
    "title" => "About",
    "slug" => "about",
  );
  $context['page']['msg'] = 'Hello';
  // simple array
  $context['cars'] = array("Volvo", "BMW", "Toyota");
  // simple array with key => value pairs
  $context['fruits'] = array(
    "fruit1" => "apple",
    "fruit2" => "orange",
  );
  // nested array (like an array of post objects)
  $context['movies'] = array(
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
  
  $template = $twig->load('about.twig');
  echo $template->render($context);
});

$routes->any('/404', function(){
  require_once($GLOBALS['twigger']);
  echo $twig->render('404.twig');
});