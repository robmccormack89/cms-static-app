<?php
namespace Rmcc;

class ArchiveController extends CoreController {
  
  // dont really need $page anymore as pagination is now based on query params. so can work to remove this 
  
  public function __construct($type, $posts_per_page = 4) {
    parent::__construct();
    $this->type = $type;
    $this->posts_per_page = $posts_per_page;
    $this->paged = $this->setPaged();
    $this->init(); // init some globals
  }
  
  private function setPaged() {
    if($this->posts_per_page == false) return false;
    return true;
  }
  
  private function init() {
    global $_context;
    $_context = array(
      'archive' => 'Archive',
      'type' => $this->type,
      'page' => 1,
      'per_page' => $this->posts_per_page,
      'paged' => $this->paged,
    );
  }
  
  public function queryMainIndexArchive($params) {

    /*
    *
    * set the _context archive
    *
    */
    $GLOBALS['_context']['archive'] = 'QueriedMainIndexArchive';
    
    /*
    *
    * parse the params string into an array (the params have been filtered for relevant ones only in routes)
    *
    */
    parse_str($params, $params_array);
    
    /*
    *
    * set the type based on the type given in routes.
    * this will be fed into the query string; type= filtering is not supposed to be used on MainIndexArchives as they are already a 'type'
    *
    */
    $params_array['type'] = typeSettingByKey('key', $this->type, 'single');
    
    /*
    *
    * set the pagination values in the params array
    *
    */
    if(isset($params_array['p'])) $GLOBALS['_context']['page'] = $params_array['p'];
    if(isset($params_array['show_all'])) $GLOBALS['_context']['paged'] = false;
    if(isset($params_array['per_page'])) $GLOBALS['_context']['per_page'] = $params_array['per_page'];
    
    /*
    *
    * rebuild the params array into a query string
    *
    */
    $pre_params = http_build_query($params_array);
    
    /*
    *
    * comma-separated items in the string: commas get changed into '%2C' after http_build_query
    * this changes fixes this.
    * cosmetic really
    *
    */
    $pre_params = str_replace("%2C", ",", $pre_params);
    
    /*
    *
    * when show_all does't have a value, it ends up with an = sign at the end after http_build_query
    * this code just removes the = from the show_all param
    * cosmetic really
    *
    */
    // if (strpos($pre_params, 'show_all') !== false) {
    //   $new_params = str_replace("show_all=", "show_all", $pre_params);
    // } else {
    //   $new_params = $pre_params;
    // }
    $new_params = showAllParamFix($pre_params);
    
    /*
    *
    * _context->string_params is what the query will be running off. so we set it here to out rebuilt string above
    *
    */
    $GLOBALS['_context']['string_params'] = $new_params;
    
    /*
    *
    * finally, set the archive obj context for twig to render
    *
    */
    $context['archive'] = (new ArchiveModel())->getQueriedArchive();
    $this->render($context);
  }
  
  public function getMainIndexArchive() {
    // set some global variables related to the current context
    $GLOBALS['_context']['archive'] = 'MainIndexArchive';
    // set the archive obj context for twig to render
    $context['archive'] = (new ArchiveModel())->getArchive();
    $this->render($context);
  }
  
  public function queryTaxTermArchive($params, $tax, $term) {
    
    /*
    *
    * set the _context archive
    *
    */
    $GLOBALS['_context']['archive'] = 'QueriedTaxTermArchive';
    
    /*
    *
    * parse the params string into an array (the params have been filtered for relevant ones only in routes)
    *
    */
    parse_str($params, $params_array);
    
    /*
    *
    * set the type & tax => term based on the data given in routes.
    * this will be fed into the query string...
    *
    */
    $params_array['type'] = typeSettingByKey('key', $this->type, 'single');
    $params_array[$tax] = $term;
    
    /*
    *
    * set the pagination values in the params array
    *
    */
    if(isset($params_array['p'])) $GLOBALS['_context']['page'] = $params_array['p'];
    if(isset($params_array['show_all'])) $GLOBALS['_context']['paged'] = false;
    if(isset($params_array['per_page'])) $GLOBALS['_context']['per_page'] = $params_array['per_page'];
    
    /*
    *
    * rebuild the params array into a query string
    *
    */
    $pre_params = http_build_query($params_array);
    
    /*
    *
    * comma-separated items in the string: commas get changed into '%2C' after http_build_query
    * this changes fixes this.
    * cosmetic really
    *
    */
    $pre_params = str_replace("%2C", ",", $pre_params);
    
    /*
    *
    * when show_all does't have a value, it ends up with an = sign at the end after http_build_query
    * this code just removes the = from the show_all param
    * cosmetic really
    *
    */
    // if (strpos($pre_params, 'show_all') !== false) {
    //   $new_params = str_replace("show_all=", "show_all", $pre_params);
    // } else {
    //   $new_params = $pre_params;
    // }
    $new_params = showAllParamFix($pre_params);
    
    
    /*
    *
    * _context->string_params is what the query will be running off. so we set it here to out rebuilt string above
    *
    */
    $GLOBALS['_context']['string_params'] = $new_params;
    
    // set the archive obj context for twig to render
    $context['archive'] = (new ArchiveModel())->getQueriedArchive();
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