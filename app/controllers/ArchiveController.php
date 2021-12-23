<?php
namespace Rmcc;

class ArchiveController extends CoreController {
  
  public function __construct() {
    parent::__construct();
    $this->init();
  }
  
  /**
   *
   * initialize the _context variable. this is used to hold data about the archive such as archive-type, content-type, taxonomy & taxonomy-term
   * On queried-archives, dont need to have the content-type, taxonomy & taxonomy-term, can instead include the query string or diseccted args
   *
   */
  
  private function init() {
    global $_context;
    $_context = array(
      'archive' => 'Archive',
    );
  }
  
  /**
   *
   * Standard (non-queried) Archive functions
   *
   */

  public function getMainIndexArchive($type) {

    global $_context;
    
    $_context['archive'] = 'MainIndexArchive';
    $_context['type'] = $type;
    
    $data['context'] = $_context;
    $data['archive'] = (new ArchiveModel())->getMainArchive($type);
    
    $this->render($data);
    
  }
  
  public function getTaxTermArchive($type, $tax, $term) {
    
    global $_context;
    
    $_context['archive'] = 'TaxTermArchive';
    $_context['type'] = $type;
    $_context['tax'] = $tax;
    $_context['term'] = $term;
    
    $data['context'] = $_context;
    $data['archive'] = (new ArchiveModel())->getTermArchive($type, $tax, $term);
    
    isset($data['archive']['title']) ? $this->render($data) : $this->error();
    
  }
  
  public function getTaxCollectionArchive($type, $tax) {
    
    global $_context;
    
    $_context['archive'] = 'TaxCollectionArchive';
    $_context['type'] = $type;
    $_context['tax'] = $tax;
    
    $data['context'] = $_context;
    $data['archive'] = (new ArchiveModel())->getTaxonomyArchive();
    
    $this->render($data);
    
  }
  
  /**
   *
   * Query Archive functions
   *
   */
  
  public function queryMainIndexArchive($type, $params) {
    
    global $_context;
    
    $_context['archive'] = 'MainIndexArchive';
    $_context['type'] = $type;
    
    $data['context'] = $_context;
    $data['archive'] = (new ArchiveModel())->getQueriedArchive($params);
    
    isset($data['archive']['title']) ? $this->render($data) : $this->error();
    
  }
  
  public function queryTaxTermArchive($type, $tax, $term, $params) {
    
    global $_context;
    
    $_context['archive'] = 'TaxTermArchive';
    $_context['type'] = $type;
    $_context['tax'] = $tax;
    $_context['term'] = $term;

    $data['context'] = $_context;
    $data['archive'] = (new ArchiveModel())->getQueriedArchive($params);
    
    isset($data['archive']['title']) ? $this->render($data) : $this->error();
    
  }
  
  public function queryTaxCollectionArchive($type, $tax, $params) {
    
    global $_context;
    
    $_context['archive'] = 'TaxTermArchive';
    $_context['type'] = $type;
    $_context['tax'] = $tax;

    $data['context'] = $_context;
    $data['archive'] = (new ArchiveModel())->getQueriedTaxonomyArchive($params);
    
    isset($data['archive']['title']) ? $this->render($data) : $this->error();
    
  }

  public function querySite($params) {
    
    global $_context;
    
    $_context['archive'] = 'SiteQuery';

    $data['context'] = $_context;
    $data['archive'] = (new ArchiveModel())->getQueriedArchive($params);
    
    isset($data['archive']['title']) ? $this->render($data) : $this->error();
    
  }
  
  /**
   *
   * Render archives thru twig templates according to a template hierarchy
   *
   */

  protected function render($context) {
    
    global $_context;
    
    $_type = (isset($_context['type'])) ? $_context['type'] : null;
    $_tax = (isset($_context['tax']) && isset($_context['type'])) ? $_context['tax'] : null;
    $_term = (isset($_context['term']) && isset($_context['tax'])) ? $_context['term'] : null;
    
    switch ($_context['archive']) {
      
      case 'MainIndexArchive':
      
        // if blog.twig exists, use it
        if($this->twig->getLoader()->exists($_type.'.twig')){
          $this->templateRender($_type.'.twig', $context);
        }
      
        // else, use archive.twig
        else {
          $this->templateRender('archive.twig', $context);
        }
        
        break;
          
      case 'TaxTermArchive':
      
        // if blog-categories-news.twig exists, use it
        if($this->twig->getLoader()->exists($_type.'-'.$_tax.'-'.$_term.'.twig')){
          $this->templateRender($_tax.'-'.$_term.'.twig', $context);
        }
      
        // else if blog.twig exists, use that
        elseif($this->twig->getLoader()->exists($_type.'.twig')) {
          $this->templateRender($_type.'.twig', $context);
        }
      
        // else, use archive.twig
        else {
          $this->templateRender('archive.twig', $context);
        }
        
        break;
          
      case 'TaxCollectionArchive':
      
        // if blog-categories.twig exists, use it
        if($this->twig->getLoader()->exists($_type.'-'.$_tax.'.twig')){
          $this->templateRender($_type.'-'.$_tax.'.twig', $context);
        }
      
        // else if blog.twig exists, use that
        elseif($this->twig->getLoader()->exists($_type.'.twig')) {
          $this->templateRender($_type.'.twig', $context);
        }
      
        // else, use archive.twig
        else {
          $this->templateRender('archive.twig', $context);
        }
        
        break;
        
      case 'SiteQuery':
      
        // if blog-search.twig exists, use it
        if($this->twig->getLoader()->exists($_type.'-search.twig')){
          $this->templateRender($_type.'-search.twig', $context);
        }
      
        // else if search.twig exists, use that
        elseif($this->twig->getLoader()->exists('search.twig')) {
          $this->templateRender('search.twig', $context);
        }
      
        // else, use archive.twig
        else {
          $this->templateRender('archive.twig', $context);
        }
        
        break;
        
      default:
        $this->templateRender('archive.twig', $context);
        
    }
    
  }
  
}