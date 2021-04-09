<?php

class Archive_controller extends Core_controller {
  
  public $archive;
  
  public function __construct()
  {
    parent::__construct();
    $this->archive = new Archive_model;
  }

  public function type_archive() {
    // archive data
    $data = $this->archive->archive_data();
    $context['archive'] = $data;
    
    $this->render_archive($context['archive'], $context);
  }
  
}