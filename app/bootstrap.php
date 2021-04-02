<?php
// Load Config file
require_once 'config/config.php';
// load vender (twig)
require_once '../vendor/autoload.php';

// spl_autoload_register(function($class_name) {
// 
//     // Define an array of directories in the order of their priority to iterate through.
//     $dirs = array(
//         'core/', // Project specific classes (+Core Overrides)
//         'controllers/', // Core classes example
//         'models/',   // Unit test classes, if using PHP-Unit
//     );
// 
//     // Looping through each directory to load all the class files. It will only require a file once.
//     // If it finds the same class in a directory later on, IT WILL IGNORE IT! Because of that require once!
//     foreach( $dirs as $dir ) {
//         if (file_exists($dir.$class_name.'.php')) {
//             require_once $dir . $className . '_model.php';
//             return;
//         }
//     }
// });

// autoload models
spl_autoload_register(function ($className) {
  
  $dirs = array(
      'core/', // Core classes example
      'controllers/', // Core classes example
      'models/',   // Unit test classes, if using PHP-Unit
  );
  
  foreach( $dirs as $dir ) {
    if (file_exists($_SERVER['DOCUMENT_ROOT'].'/app\/' .$dir. '' .$className. '.php')) {
      // require_once($dir.'class.'.strtolower($class_name).'.php');
      require_once $dir . $className . '.php';
      echo 'yeah';
      // return;
    } else {
      echo 'no';
    }
  }
  // 
  // require_once 'core/' . $className . '.php';
  
});
// Init Core Library
$Core_controller  = new Core_controller;
// spl_autoload_register(function ($className) {
//   require_once 'models/' . $className . '_model.php';
// });
// load routes. Each route runs a controller method
require_once('config/routes.php');