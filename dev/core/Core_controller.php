<?php

class Core_controller {
  
  public function __construct() {
    // require the core model & create new core object
    // require_once('../app/core/Core_model.php');
    // $this->core = new Core_model;
  }
  
  // call this function in routes for the error page.
  // subsequent controllers do their own render functions which are called from Core_model for cahcing & template checking
  public function error() {
    echo $this->twig->render('404.twig');
  }
  
}