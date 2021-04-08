<?php
// loads configs & helpers some helper functions, for now. A work in progress...
require_once 'helpers/helpers.php';
// composer vender autoload
require_once '../vendor/autoload.php';
// autoload the core, controllers & models
spl_autoload_register(function ($className) {
  
  $dirs = array(
    'core/', // Core classes
    'controllers/', // Controllers
    'models/',   // Models
  );
  
  $fileFormats = array(
    '%s.php'
    // '%s.php',
    // 'class.%s.php',
    // '%s.inc.php'
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
// load routes. Each route runs a controller method
require_once('config/routes.php');