<?php
namespace Rmcc;

class ArchiveController extends CoreController {
  
  public function __construct($type, $key, $paged = false, $page = null, $posts_per_page = 4){
    parent::__construct();
    $this->type = $type; // string. e.g 'blog' or 'portfolio'
    $this->key = $key; // string. e.g 'posts' or 'projects'
    $this->paged = $paged; // boolean. setting. whether or not the archive is paged. defaults to false
    $this->page = $page; // int. requested paginated page number. passed on via routes. defaults to null
    $this->posts_per_page = $posts_per_page; // int. setting. how many posts to show per page on this archive. optional, defaults to 4
    
    $this->archive = new ArchiveModel($this->type, $this->key, $this->paged, $this->page, $this->posts_per_page);
  }
  
  // main archives like the Blog or Portfolio archives. Not term or taxonomy archives!
  public function mainIndexArchive() {
    $context['archive'] = $this->archive->getArchive();
    
    $this->render($context);
  }
  
  protected function render($context) {
    if ($this->twig->getLoader()->exists($this->type.'.twig')) {
      $this->templateRender($this->type.'.twig', $context);
    } else {
      $this->templateRender('archive.twig', $context);
    }
  }
}