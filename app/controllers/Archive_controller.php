<?php

class Archive_controller extends Core_controller {
  
  public $archive;
  
  public function __construct()
  {
    parent::__construct();
    $this->archive = new Archive_model;
  }

  public function the_archive() {
    $context['archive'] = $this->archive->the_archive();
    $this->render_archive($context['archive'], $context);
  }
  
  public function render_archive($obj, $context) {
    if ($obj['name'] == 'blog') {
      $this->render_template('blog.twig', 'archive.twig', '404.twig', $context);
    } elseif ($obj['name'] == 'portfolio') {
      $this->render_template('portfolio.twig', 'archive.twig', '404.twig', $context);
    };
  }
  
}