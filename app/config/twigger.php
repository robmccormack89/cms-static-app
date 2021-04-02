<?php
require_once '../vendor/autoload.php';

$views = array(
  '../app/views/',
  '../app/views/pages',
  '../app/views/parts'
);
$loader = new \Twig\Loader\FilesystemLoader($views);

$SiteTitle = 'Your Site Title';
$SomeOtherVariable = 'Lorem Ipsum Dolor';
$dateYear = date("Y");

$twig = new \Twig\Environment($loader);

$twig->addGlobal('SiteTitle', $SiteTitle);
$twig->addGlobal('SomeOtherVariable', $SomeOtherVariable);
$twig->addGlobal('date_year', $dateYear );