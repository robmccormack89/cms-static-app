<?php
// composer autoload
require_once('vendor/autoload.php');

// spl_autoload_register the core, controllers & models
spl_autoload_register(function ($class) {
  $dirs = array(
    'core/', // Core classes
    'controllers/', // Controllers
    'models/',   // Models
  );
  $formats = array(
    '%s.php'
  );
  foreach( $dirs as $dir ) {
    foreach( $formats as $format ) {
      $path = $dir.sprintf($format, $class);
      if (file_exists($_SERVER['DOCUMENT_ROOT'].'/app\/' . $path)) {
        require_once $path;
        return;
      }
    }
  }
});

// load configs as variable $configs
$configs = require_once('config/config.php');

// load helpers
require_once('helpers/cache.php');
require_once('helpers/functions.php');

// load routes
require_once('config/routes.php');