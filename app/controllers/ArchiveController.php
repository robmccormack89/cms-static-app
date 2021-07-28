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
  
  public function queryMainIndexArchive($params) {

    $GLOBALS['_context']['archive'] = 'QueriedMainIndexArchive'; //
    
    parse_str($params, $params_array); //
    
    $params_array['type'] = typeSettingByKey('key', $this->type, 'single');
    
    if(isset($params_array['p'])) $GLOBALS['_context']['page'] = $params_array['p'];
    if(isset($params_array['show_all'])) $GLOBALS['_context']['paged'] = false;
    if(isset($params_array['per_page'])) $GLOBALS['_context']['per_page'] = $params_array['per_page'];
    
    $pre_params = http_build_query($params_array);
    $pre_params = str_replace("%2C", ",", $pre_params);
    if (strpos($pre_params, 'show_all') !== false) {
      $new_params = str_replace("show_all=", "show_all", $pre_params);
    } else {
      $new_params = $pre_params;
    }
    $GLOBALS['_context']['string_params'] = $new_params;
    
    // set the archive obj context for twig to render
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