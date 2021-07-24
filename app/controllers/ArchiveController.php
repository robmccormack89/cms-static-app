<?php
namespace Rmcc;

class ArchiveController extends CoreController {
  
  /*
  *
  * This class is used to render MainIndexArchives like 'blog' or 'portfolio'
  * Create a new ArchiveController() object & call the getMainIndexArchive() method to render the MainIndexArchive
  * $type is required a required property; rest have defaults
  *
  */
  public function __construct(
    string $type, // e.g 'blog' or 'portfolio'. required
    bool $paged = false, // true or false. set whether this archive should be paged
    int $page = null, // if archive is paged, this would be the requested page (e.g 2, 3 or 4)
    int $posts_per_page = 4 // how many items to display per page
  )
  {
    parent::__construct();
    $this->type = $type;
    $this->paged = $paged;
    $this->page = $page; 
    $this->posts_per_page = $posts_per_page; 
  }
  
  public function getMainIndexArchive() {
    $context['archive'] = (new ArchiveModel($this->type, $this->paged, $this->page, $this->posts_per_page))->getArchive();
    $this->render($context);
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
    // do something about the empty check here
    // in some cases if no posts are retuned, there should be an error, like non-existent paged pages...
    // but in other cases there should be a 'no posts found' message, like an existing but empty content type...
    // just disable this check for now
    // (!empty($context['archive']['posts'])) ? $do_something : $this->error();
    ($this->twig->getLoader()->exists($this->type.'.twig')) ? $this->templateRender($this->type.'.twig', $context) : $this->templateRender('archive.twig', $context);
  }
  
}