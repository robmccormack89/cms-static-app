<?php
namespace Rmcc;

class ArchiveController extends CoreController {
  
  public function __construct() {
    parent::__construct();
    $this->init();
  }
  
  /**
   *
   * initialize the global _context variable.
   * this will be used to hold data about the archive such as archive-type(archive), content-type(type), taxonomy(tax) & taxonomy-term(term)
   * 
   * We should be careful about what methods downstream rely on global _context variables & what variables they need
   *
   * Currently, in QueryModel(), only the meta methods (for queried object data), rely on the global _context type, tax & term variables
   * PaginationModel() has been freed from dependency of _context variables. This now uses class inputs
   * $this->render() relies on global _context's archive, type, tax & term values
   *
   * On querySite archives, we dont need to have type, taxonomy & term
   * we can instead include the query string or dissected args in the global _context
   * or we can take the type, tax or term from the query string, if they exist, & add them to the _context downstream
   * only problem is that term can be an array(comma-separated values) in the query string. maybe this can be added as 'terms' instead to the global _context
   *
   * only TaxTerm (maybe querySite) archives require to throw the error() if archive.title not set. all rest willhave errors thru SingleController->SingleModel
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

    global $_context; // get the global _context
    
    $_context['archive'] = 'MainIndexArchive'; // reset 'archive' in _context
    $_context['type'] = $type; // add the type to _context
    
    $data['context'] = $_context; // add the _context data to the render data
    $data['archive'] = (new ArchiveModel())->getMainArchive(); // add the archive data to the render data using ArchiveModel())->getMainArchive()
    
    $this->render($data); // render the data. see render() below for template hierarchy
    
  }
  
  public function getTaxTermArchive($type, $tax, $term) {
    
    global $_context;
    
    $_context['archive'] = 'TaxTermArchive';
    $_context['type'] = $type;
    $_context['tax'] = $tax; // add the tax to _context (needed for TaxTerm & Collection archives)
    $_context['term'] = $term; // add the term to _context (needed for TaxTerm archives only)
    
    $data['context'] = $_context;
    $data['archive'] = (new ArchiveModel())->getTermArchive();
    
    // this is if $term is invalid. invalid $type & $tax in url get treated as invalid single-page urls & errors happen thru SingleController->SingleModel
    isset($data['archive']['title']) ? $this->render($data) : $this->error();
    
  }
  
  public function getTaxCollectionArchive($type, $tax) {
    
    global $_context;
    
    $_context['archive'] = 'TaxCollectionArchive';
    $_context['type'] = $type;
    $_context['tax'] = $tax; // Collection archives only need $type & $tax
    
    $data['context'] = $_context;
    $data['archive'] = (new ArchiveModel())->getTaxonomyArchive();
    
    $this->render($data);
    
  }
  
  /**
   *
   * Query Archive functions
   *
   * $params is the query string provided thru archived.routes
   * $params has been checked for valid parameters, but has not been sanitized in any way...
   *
   */
  
  public function queryMainIndexArchive($type, $params) {
    
    global $_context;
    
    $_context['archive'] = 'MainIndexArchive';
    $_context['type'] = $type;
    
    $data['context'] = $_context;
    $data['archive'] = (new ArchiveModel())->getQueriedArchive($params);
    
    $this->render($data);
    
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
    
    $this->render($data);
    
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
   * Uses the global _context's archive, type, tax & term values
   * Differenciates between MainIndex, TaxTerm, TaxCollection & SiteQuery archives
   * See switch statement to see the template hierarchy for each archive-type
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