<?php
namespace Rmcc;

class ArchiveController extends CoreController {
  
  public function __construct($type = null, $posts_per_page = 7) {
    parent::__construct();
    $this->type = $type;
    $this->posts_per_page = typeSettingByKey('key', $this->type, 'per_page') ?? $posts_per_page;
    $this->paged = $this->paged = typeSettingByKey('key', $this->type, 'per_page') ? true : false;
    $this->init(); // init some globals
  }
  
  /**
   *
   * Initialization functions
   *
   */
  private function init() {
    // set the global _context array, to be used downstream...
    global $_context;
    $_context = array(
      'archive' => 'Archive', // this will be overriden for specific archive types below
      'type' => $this->type, // should be a string with term like 'blog' or 'portfolio'. will be the $key variable in routes.archived.php
      'page' => 1, // queried archives will deal with pagination, so only the query functions will need override this value. so default to 1
      'per_page' => $this->posts_per_page,// if 'per_page' in $config is not set, $this->posts_per_page will default to 7 (see above) & $this->paged will be set to false
      'paged' => $this->paged, // this is the desired behaviour for now. it is okay to leave 'per_page' defraulting to 7 whilst 'paged' is set to false. 'paged' will override 'per_page' & produce a non-paged archive
    );
  }
  
  /**
   *
   * Archive functions
   * 
   * 3 types of archive for the app: MainIndexArchive, TaxTermArchive & TaxCollectionArchive
   * These are relatively straight-forward
   *
   */
  
  // 'blog'
  public function getMainIndexArchive() {
    
    // get the global _context variables
    global $_context;
    
    // reset the global _context variable for 'archive' to 'MainIndexArchive'
    $_context['archive'] = 'MainIndexArchive';
    
    // get the archive object context from ArchiveModel -> getArchive() for twig to render
    $context['archive'] = (new ArchiveModel())->getArchive();

    // add the global _content variables to the archive object context
    $context['context'] = $_context;
    
    // render the context. no need to throw errors here as MainIndexArchive is created using $config['types']
    $this->render($context);
    
  }
  
  /**
   * 'blog/categories/news'
   *
   * @param string $tax - taxonomy e.g: 'categories'
   * @param string $term - taxonomy term e.g: 'news'
   */
  public function getTaxTermArchive($tax, $term) {
    
    // get the global _context variables
    global $_context;
    
    // reset the global _context variable for 'archive' to 'TaxTermArchive'
    $_context['archive'] = 'TaxTermArchive';
    
    // add 'tax' & 'term' to global _context variables
    $_context['tax'] = $tax;
    $_context['term'] = $term;
    
    // get the archive object context from ArchiveModel -> getTermArchive() for twig to render
    $context['archive'] = (new ArchiveModel())->getTermArchive();
    
    // add the global _content variables to the archive object context
    $context['context'] = $_context;
    
    // render the archive object content ONLY if archive.title exists, else throw an error
    // the idea here being that if a term is not valid, the archive.title wont be set at all
    isset($context['archive']['title']) ? $this->render($context) : $this->error();
    
  }
  
  /**
   * 'blog/categories'
   *
   * @param string $tax - taxonomy e.g: 'categories'
   */
  public function getTaxCollectionArchive($tax) {
    
    // get the global _context variables
    global $_context;
    
    // reset the global _context variable for 'archive' to 'TaxCollectionArchive'
    $_context['archive'] = 'TaxCollectionArchive';
    
    // add 'tax' to global _context variables
    $_context['tax'] = $tax;
    
    // get the archive object context from ArchiveModel -> getTaxonomyArchive() for twig to render
    $context['archive'] = (new ArchiveModel())->getTaxonomyArchive();
    
    // add the global _content variables to the archive object context
    $context['context'] = $_context;
    
    // render the context. no need to throw errors here as TaxCollectionArchive is created using $config['types']['blog']['taxes_in_meta']
    $this->render($context);
    
  }

  /**
   *
   * Queried Archive functions
   * 
   * 4 types of queriable archives for the app: querySite, queryMainIndexArchive, queryTaxTermArchive & queryTaxCollectionArchive
   * These are more complicated than standard non-queriable archives
   *
   */
  
  // '?type=blog&categories=news&p=2'
  public function querySite($params) {
    
    global $_context;

    /*
    *
    * set the _context archive
    *
    */
    $_context['archive'] = 'SiteQuery';
    
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
    // $params_array['type'] = typeSettingByKey('key', $this->type, 'single');
    
    /*
    *
    * set the pagination values in the params array
    *
    */
    if(isset($params_array['p'])) $_context['page'] = $params_array['p'];
    if(isset($params_array['show_all'])) $_context['paged'] = false;
    if(isset($params_array['per_page'])) $_context['per_page'] = $params_array['per_page'];
    if(!isset($params_array['per_page'])) $params_array['per_page'] = $_context['per_page'];
    
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
    $new_params = showAllParamFix($pre_params);
    
    /*
    *
    * _context->string_params is what the query will be running off. so we set it here to out rebuilt string above
    *
    */
    $_context['string_params'] = $new_params;
    
    /*
    *
    * finally, set the archive obj context for twig to render
    *
    */
    $context['archive'] = (new ArchiveModel())->getQueriedArchive();
    $context['context'] = $_context;
    if(isset($context['archive']['title'])) {
      $this->render($context);
    } else {
      $this->error();
    }
  }
  
  // 'blog?categories=news&p=2'
  public function queryMainIndexArchive($params) {
    
    global $_context;

    /*
    *
    * set the _context archive
    *
    */
    $_context['archive'] = 'MainIndexArchive';
    
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
    if(isset($params_array['p'])) $_context['page'] = $params_array['p'];
    if(isset($params_array['show_all'])) $_context['paged'] = false;
    if(isset($params_array['per_page'])) $_context['per_page'] = $params_array['per_page'];
    if(!isset($params_array['per_page'])) $params_array['per_page'] = $_context['per_page'];
    
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
    $new_params = showAllParamFix($pre_params);
    
    /*
    *
    * _context->string_params is what the query will be running off. so we set it here to out rebuilt string above
    *
    */
    $_context['string_params'] = $new_params;
    
    /*
    *
    * finally, set the archive obj context for twig to render
    *
    */
    $context['archive'] = (new ArchiveModel())->getQueriedArchive();
    $context['context'] = $_context;
    if(isset($context['archive']['title'])) {
      $this->render($context);
    } else {
      $this->error();
    }
  }
  
  // 'blog/categories/news?p=2'
  public function queryTaxTermArchive($params, $tax, $term) {
    
    global $_context;
    
    /*
    *
    * set the _context archive
    *
    */
    $_context['archive'] = 'TaxTermArchive';
    
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
    * add tax & term to the _context array
    *
    */
    $_context['tax'] = $tax;
    $_context['term'] = $term;
    
    /*
    *
    * set the pagination values in the params array
    *
    */
    if(isset($params_array['p'])) $_context['page'] = $params_array['p'];
    if(isset($params_array['show_all'])) $_context['paged'] = false;
    if(isset($params_array['per_page'])) $_context['per_page'] = $params_array['per_page'];
    if(!isset($params_array['per_page'])) $params_array['per_page'] = $_context['per_page'];
    
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
    $new_params = showAllParamFix($pre_params);
    
    
    /*
    *
    * _context->string_params is what the query will be running off. so we set it here to out rebuilt string above
    *
    */
    $_context['string_params'] = $new_params;
    
    // set the archive obj context for twig to render
    $context['archive'] = (new ArchiveModel())->getQueriedArchive();
    $context['context'] = $_context;
    if(isset($context['archive']['title'])) {
      $this->render($context);
    } else {
      $this->error();
    }
  }
  
  // 'blog/categories?p=2'
  public function queryTaxCollectionArchive($params, $tax) {
    
    global $_context;

    /*
    *
    * set the _context archive
    *
    */
    $_context['archive'] = 'TaxCollectionArchive';
    
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
    $params_array['taxonomy'] = taxSettingByKey($this->type, 'key', $tax, 'single');
    
    /*
    *
    * add tax & term to the _context array
    *
    */
    $_context['tax'] = $tax;
    
    /*
    *
    * set the pagination values in the params array
    *
    */
    if(isset($params_array['p'])) $_context['page'] = $params_array['p'];
    if(isset($params_array['show_all'])) $_context['paged'] = false;
    if(isset($params_array['per_page'])) $_context['per_page'] = $params_array['per_page'];
    if(!isset($params_array['per_page'])) $params_array['per_page'] = $_context['per_page'];
    
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
    $new_params = showAllParamFix($pre_params);
    
    /*
    *
    * _context->string_params is what the query will be running off. so we set it here to out rebuilt string above
    *
    */
    $_context['string_params'] = $new_params;   
      
    // set the archive obj context for twig to render
    $context['archive'] = (new ArchiveModel())->getQueriedTaxonomyArchive();
    $context['context'] = $_context;
    if(isset($context['archive']['title'])) {
      $this->render($context);
    } else {
      $this->error();
    }
  }
  
  /**
   * Render function for archives
   * A template-hierarchy rendering function for twig templates
   *
   * This will check the global $_context['archive'] to see what type of archive we are dealing with
   *
   * 1. TaxTermArchive's render format = _tax-_term.twig
   * 2. TaxCollectionArchive's render format = _type-_tax.twig
   * 3. MainIndexArchive's render format = _type.twig
   *
   * if global $_context['archive'] is not one of the above, render archive.twig
   * 'blog/categories'
   *
   * @param object|array $context - the context for twig to render
   */
  protected function render($context) {
    
    // get the global _context variables
    global $_context;
    
    $_type = (isset($_context['type'])) ? $_context['type'] : null;
    $_tax = (isset($_context['tax']) && isset($_context['type'])) ? $_context['tax'] : null;
    $_term = (isset($_context['term']) && isset($_context['tax'])) ? $_context['term'] : null;
    
    // TaxTermArchive
    if($_context['archive'] = 'TaxTermArchive' && $this->twig->getLoader()->exists($_tax.'-'.$_term.'.twig')) {
      $this->templateRender($_tax.'-'.$_term.'.twig', $context); // categories-news.twig
      exit();
    }
    
    // TaxCollectionArchive
    elseif($_context['archive'] = 'TaxCollectionArchive' && $this->twig->getLoader()->exists($_type.'-'.$_tax.'.twig')) {
      $this->templateRender($_type.'-'.$_tax.'.twig', $context); // blog-categories.twig
      exit();
    }
    
    // MainIndexArchive
    elseif($_context['archive'] = 'MainIndexArchive' && $this->twig->getLoader()->exists($_type.'.twig')) {
      $this->templateRender($_type.'.twig', $context); // blog.twig
      exit();
    }
    
    // All else
    else {
      $this->templateRender('archive.twig', $context);
    }
    
  }
  
}