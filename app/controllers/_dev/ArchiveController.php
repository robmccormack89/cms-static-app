<?php
namespace Rmcc;

class ArchiveController extends CoreController {
  
  public function __construct($type, $page = 1, $posts_per_page = 4) {
    parent::__construct();
    $this->type = $type;
    $this->page = $page;
    $this->posts_per_page = $posts_per_page;
    $this->paged = $this->setPaged();
    $this->init(); // init some globals
  }
  
  private function init() {
    $GLOBALS['query_context'] = 'Archive';
    $GLOBALS['query_type'] = $this->type;
    $GLOBALS['query_page'] = $this->page;
    $GLOBALS['query_per_page'] = $this->posts_per_page;
    $GLOBALS['query_paged'] = $this->paged;
  }
  
  private function setPaged() {
    if($this->page == false) return false;
    return true;
  }
  
  public function getMainIndexArchive() {
    // set some global variables related to the current context
    $GLOBALS['query_context'] = 'MainIndexArchive';
    // set the archive obj context for twig to render
    $context['archive'] = (new ArchiveModel())->getArchive();
    $this->render($context);
  }
  
  public function getTaxTermArchive($tax, $term) {
    // set some global variables related to the current context
    $GLOBALS['query_context'] = 'TaxTermArchive';
    $GLOBALS['query_tax'] = $tax;
    $GLOBALS['query_term'] = $term;
    // set the archive obj context for twig to render
    $context['archive'] = (new TermArchiveModel())->getTermArchive();
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