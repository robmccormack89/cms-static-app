<?php

class Archives_controller extends Core_controller {
  
  public function __construct()
  {
    parent::__construct();
  }

  public function do_the_archive() {
    $context['title'] = 'Homepage';
  }
  
}