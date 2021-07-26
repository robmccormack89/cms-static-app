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
  public function __construct() {
    parent::__construct();
  }
  
  public function getMainIndexArchive(
    string $type, // e.g 'blog' or 'portfolio'. required
    bool $paged = true, // true or false. set whether this archive should be paged
    int $page = 1, // if archive is paged, this would be the requested page (e.g 2, 3 or 4)
    int $posts_per_page = 4 // how many items to display per page
  )
  {
    $context['archive'] = (new ArchiveModel($type, $paged, $page, $posts_per_page))->getArchive();
    $this->render($context);
  }
  
  public function getTaxTermArchive(
    string $type, // e.g 'blog' or 'portfolio'. required
    string $tax, // e.g 'categories' or 'tags'. required
    string $term, // e.g 'news' or 'twig'. usually wlll be from the request
    bool $paged = true, // true or false. set whether this archive should be paged
    int $page = 1, // if archive is paged, this would be the requested page e.g 2, 3 or 4 etc
    int $posts_per_page = 4 // how many items to display per page, defaults to 4
  ) 
  {
    $context['archive'] = (new TermArchiveModel($type, $tax, $term, $paged, $page, $posts_per_page))->getTermArchive();
    $this->render($context);
  }
  
  public function getTaxCollectionArchive(
    string $type, // e.g 'blog' or 'portfolio'. required
    string $tax, // e.g 'categories' or 'tags'. required
    bool $paged = true, // true or false. set whether this archive should be paged
    int $page = 1, // if archive is paged, this would be the requested page e.g 2, 3 or 4 etc
    int $posts_per_page = 4 // how many items to display per page, defaults to 4
  ) 
  {
    $context['archive'] = (new TaxonomyArchiveModel($type, $tax, $paged, $page, $posts_per_page))->getTaxonomyArchive();
    $this->render($context);
  }
  
  protected function render($context) {
    $this->templateRender('archive.twig', $context);
  }
  // protected function _old_render($context, $type) {
  //   /*
  //   *
  //   * This method is used to render archival objects according to a template hierarchy.
  //   * The first check is to see if posts is not empty,
  //   * If posts is not empty then it checks to see if a file exists using the format: $type.twig e.g 'blog.twig' or 'portfolio.twig'
  //   * If that template doesn't exist, then render the archive using archive.twig. blog.twig would supercede archive.twig
  //   *
  //   */
  //   // do something about the empty check here
  //   // in some cases if no posts are returned, there should be an error, like non-existent paged pages...
  //   // but in other cases there should be a 'no posts found' message, like an existing but empty content type...
  //   // just disable this check for now
  //   // (!empty($context['archive']['posts'])) ? $do_something : $this->error();
  //   // also, should allow for control between mainindex archive templates, term archive templates & tax archive templates etc
  //   ($this->twig->getLoader()->exists($type.'.twig')) ? $this->templateRender($type.'.twig', $context) : $this->templateRender('archive.twig', $context);
  // }
  
}