<?php

$routes->get('/', function(){
  require_once($GLOBALS['twigger']);
  echo $twig->render('homepage.twig');
});