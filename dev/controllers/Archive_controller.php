<?php

class Archive_controller extends Core_controller {
  
  public function __construct()
  {
    parent::__construct();
    $this->archive = new Archive_model;
  }

  public function archive() {
    $context['archive'] = $this->archive->get_archive();

    if ($context['archive']) {
      $this->archive->render_archive($context['archive'], $context);
    } else {
      $this->error();
    }
  }
  
}