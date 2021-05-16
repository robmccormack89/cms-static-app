<?php
namespace Rmcc;

class ArchiveController extends CoreController {
  
  public function __construct(){
    parent::__construct();
  }
  
  public function getMainIndexArchive($type, $key, $taxonomies = null, $paged = false, $page = null, $posts_per_page = 4) {
    $archive = new ArchiveModel($type, $key, $taxonomies, $paged, $page, $posts_per_page);
    $context['archive'] = $archive->getArchive();

    $this->render($context, $type);
  }
  
  protected function render($context, $type) {
    if($context['archive']['posts']) {
      if ($this->twig->getLoader()->exists($type.'.twig')) {
        $this->templateRender($type.'.twig', $context);
      } else {
        $this->templateRender('archive.twig', $context);
      }
    } else {
      $this->error();
    }
  }
}