<?php
// composer autoload
require_once '../vendor/autoload.php';
// spl_autoload_register the core, controllers & models
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
// load configs
$configs = require_once('config/config.php');
// load helpers
require_once 'helpers/helpers.php';
// load routes
require_once 'config/routes.php';