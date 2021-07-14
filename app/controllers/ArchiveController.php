<?php
namespace Rmcc;

class ArchiveController extends CoreController {
  
  /*
  *
  * This class is used to render archival objects like blog & portfolio etc.
  * Create a new ArchiveController object with $type, $paged, $page & $posts_per_page properties. $type is required
  * Call the getMainIndexArchive() method on the archival object to render it.
  * This is mainly for use within a routing context, see config/routes.
  *
  */
  public function __construct(string $type, bool $paged = false, int $page = null, int $posts_per_page = 4){
    parent::__construct();
    $this->type = $type; // e.g 'blog' or 'portfolio'. required
    $this->paged = $paged; // true or false. set whether this archive should be paged
    $this->page = $page; // if archive is paged, this would be the requested page e.g 2, 3 or 4 etc
    $this->posts_per_page = $posts_per_page; // how many items to display per page, defaults to 4
  }
  
  public function getMainIndexArchive() {
    $context['archive'] = (new ArchiveModel($this->type, $this->paged, $this->page, $this->posts_per_page))->archive; // get the archival object context using the ArchiveModel class
    $this->render($context); // render the context
  }
  
  /*
  *
  * This method is used to render archival objects according to a template hierarchy.
  * The first check is to see if posts is not empty,
  * If posts is not empty then it checks to see if a file exists using the format: $type.twig e.g 'blog.twig' or 'portfolio.twig'
  * If that template doesn't exist, then render the archive using archive.twig. blog.twig would supercede archive.twig
  *
  */
  protected function render($context) {
    if(!empty($context['archive']['posts'])) {
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