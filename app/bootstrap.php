<?php
// Load Config file
require_once 'config/config.php';
// load vender (twig)
require_once '../vendor/autoload.php';

// autoload models
spl_autoload_register(function ($className) {
  
  $dirs = array(
    'core/', // Core classes
    'controllers/', // Controllers
    'models/',   // Models
  );
  
  $fileFormats = array(
    '%s.php',
    '%s_model.php',
    'class.%s.php',
    '%s.inc.php'
  );
  
  foreach( $dirs as $dir ) {
    foreach( $fileFormats as $fileFormat ) {
      $path = $dir.sprintf($fileFormat, $className);
      if (file_exists($_SERVER['DOCUMENT_ROOT'].'/app\/' . $path)) {
        require_once $path;
        return;
      }
    }
  }
  
});
// Init Core Library
$Core_controller  = new Core_controller;
$Home_controller  = new Home_controller;

// load routes. Each route runs a controller method
require_once('config/routes.php');