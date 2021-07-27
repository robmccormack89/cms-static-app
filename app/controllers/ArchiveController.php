<?php
namespace Rmcc;

class ArchiveController extends CoreController {
  
  public function __construct($type, $page = 1, $posts_per_page = 4) {
    parent::__construct();
    $this->type = $type;
    $this->page = $page;
    $this->posts_per_page = $this->setPerPage($posts_per_page);
    $this->paged = $this->setPaged();
    $this->init(); // init some globals
  }
  
  private function setPaged() {
    if($this->page == false) return false;
    return true;
  }
  private function setPerPage($posts_per_page) {
    if($this->page) return $posts_per_page;
    return false;
  }
  
  private function init() {
    global $_context;
    $_context = array(
      'archive' => 'Archive',
      'type' => $this->type,
      'page' => $this->page,
      'per_page' => $this->posts_per_page,
      'paged' => $this->paged,
    );
  }
  
  public function getMainIndexArchive() {
    // set some global variables related to the current context
    $GLOBALS['_context']['archive'] = 'MainIndexArchive';
    // set the archive obj context for twig to render
    $context['archive'] = (new ArchiveModel())->getArchive();
    $this->render($context);
  }
  
  public function getTaxTermArchive($tax, $term) {
    // set some global variables related to the current context
    $GLOBALS['_context']['archive'] = 'TaxTermArchive';
    $GLOBALS['_context']['tax'] = $tax;
    $GLOBALS['_context']['term'] = $term;
    // set the archive obj context for twig to render
    $context['archive'] = (new ArchiveModel())->getTermArchive();
    $this->render($context);
  }
  
  protected function render($context) {
    $this->templateRender('archive.twig', $context);
  }
  
}