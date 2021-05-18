<?php
namespace Rmcc;

class ArchiveController extends CoreController {
  
  public function __construct($type, $paged = false, $page = null, $posts_per_page = 4){
    parent::__construct();
    $this->type = $type;
    $this->paged = $paged;
    $this->page = $page;
    $this->posts_per_page = $posts_per_page;
  }
  
  public function getMainIndexArchive() {
    $archive_obj = new ArchiveModel($this->type, $this->paged, $this->page, $this->posts_per_page);
    $context['archive'] = $archive_obj->archive;

    $this->render($context);
  }
  
  protected function render($context) {
    if($context['archive']['posts']) {
      if ($this->twig->getLoader()->exists($this->type.'.twig')) {
        $this->templateRender($this->type.'.twig', $context);
      } else {
        $this->templateRender('archive.twig', $context);
      }
    } else {
      $this->error();
    }
  }
}