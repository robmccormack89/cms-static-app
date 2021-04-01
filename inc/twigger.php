<?php

require_once $GLOBALS['root'] . "/vendor/autoload.php";

$viewsDir = array(
  'views/',
  'views/pages',
  'views/parts'
);
$loader = new \Twig\Loader\FilesystemLoader($viewsDir);

$SiteTitle = 'Your Site Title';
$SomeOtherVariable = 'Lorem Ipsum Dolor';
$dateYear = date("Y");

$twig = new \Twig\Environment($loader);

$twig->addGlobal('SiteTitle', $SiteTitle);
$twig->addGlobal('SomeOtherVariable', $SomeOtherVariable);
$twig->addGlobal('date_year', $dateYear );