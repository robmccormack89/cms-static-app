<?php
// Load Config
require_once 'config/config.php';
// load twig
require_once('config/twigger.php');
// load core classes
spl_autoload_register(function ($className) {
  require_once 'models/' . $className . '_model.php';
});
// load routes setup
require_once('config/routes.php');