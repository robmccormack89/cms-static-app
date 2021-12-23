<?php
namespace Rmcc;

class ArchiveController extends CoreController {
  
  public function __construct($type = null) {
    parent::__construct();
    $this->type = $type;
    $this->init(); // init globals
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
      'type' => $this->type, // $key variable in routes.archived, should be a string like 'blog' or 'portfolio'. if type is not inputted into class, will need to set the global type in the relevant method (like querySite)
      'page' => 1, // queried archives will deal with pagination, so only the query functions will need override this value. so default to 1
      'per_page' => 7, // this will be overriden below, should be gotten from the type's meta & if not existing there, set $paged to false
      'paged' => true, // this will be overriden below...
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

  /**
   * 'blog'
   *
   * errors in any term in the url here will result in error thrown from SingleController - treated as invalid singular URL - this is normal behaviour
   * only query functions for MainIndexArchive will themselves throw errors
   *
   */
  public function getMainIndexArchive() {
    
    // get the global _context variables
    global $_context;
    
    // reset the global _context variables
    $_context['archive'] = 'MainIndexArchive';
    
    // if type's 'per_page' setting exists, set global 'per_page' to it, else set global 'paged' to false (per_page will be 7 otherwise & paged will be true)
    if(typeSettingByKey('key', $this->type, 'per_page')) {
       $_context['per_page'] = typeSettingByKey('key', $this->type, 'per_page');
    } else {
      $_context['per_page'] = null; // might as well set this to null? per_page shoould be okay at null if pagwed is false
      $_context['paged'] = false;
    }
    
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
   * if 'blog' or 'categories' terms in url are invalid, error will be thrown thru SingleController (treated as invalid singular URL)
   * if 'news' is invalid, the archive.title will fail to be created, & we will throw an error in this function based on that (see below)
   * else, we render the context. the idea being that if a term is not valid, the archive.title wont be set at all. thi is desired behaviour for now
   *
   * @param string $tax - taxonomy e.g: 'categories'
   * @param string $term - taxonomy term e.g: 'news'
   */
  public function getTaxTermArchive($tax, $term) {
    
    // get the global _context variables
    global $_context;
    
    // reset the global _context variable for 'archive' to 'TaxTermArchive'
    $_context['archive'] = 'TaxTermArchive';
    
    // if type's 'per_page' setting exists, set global 'per_page' to it, else set global 'paged' to false (per_page will be 7 otherwise & paged will be true)
    if(typeSettingByKey('key', $this->type, 'per_page')) {
       $_context['per_page'] = typeSettingByKey('key', $this->type, 'per_page');
    } else {
      $_context['paged'] = false;
    }
    
    // add 'tax' & 'term' to global _context variables
    $_context['tax'] = $tax;
    $_context['term'] = $term;
    
    // get the archive object context from ArchiveModel -> getTermArchive() for twig to render
    $context['archive'] = (new ArchiveModel())->getTermArchive();
    
    // add the global _content variables to the archive object context
    $context['context'] = $_context;
    
    // render the archive object content ONLY if archive.title exists, else throw an error
    isset($context['archive']['title']) ? $this->render($context) : $this->error();
    
  }
  
  /**
   * 'blog/categories'
   *
   * errors in any term in the url here will result in error thrown from SingleController - treated as invalid singular URL - this is normal behaviour
   * only query functions for TaxCollectionArchive will themselves throw errors
   *
   * @param string $tax - taxonomy e.g: 'categories'
   */
  public function getTaxCollectionArchive($tax) {
    
    // get the global _context variables
    global $_context;
    
    // reset the global _context variable for 'archive' to 'TaxCollectionArchive'
    $_context['archive'] = 'TaxCollectionArchive';
    
    // if type's 'per_page' setting exists, set global 'per_page' to it, else set global 'paged' to false (per_page will be 7 otherwise & paged will be true)
    if(typeSettingByKey('key', $this->type, 'per_page')) {
       $_context['per_page'] = typeSettingByKey('key', $this->type, 'per_page');
    } else {
      $_context['paged'] = false;
    }
    
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
  
  /**
   *
   * Query the MainIndexArchive - This seems working. Double-check
   *
   * WORKING URLS
   * 'blog?categories=news&p=2'
   * 'blog?categories=events&show_all'
   * 'blog?categories=events,news&tags=javascript,twig&s=handshake&year=2021&month=4&day=4&name=wordpress'
   * 'blog?categories=events&tags=twig&year=2021&month=4&day=4&show_all'
   * 'blog?categories=events&tags=twig&year=2021&month=4&day=4&per_page=3&p=2'
   *
   * 'events?categories=sport&p=2'
   * 'events?categories=awards&show_all'
   * 'events?categories=awards,sport&locations=dublin&s=handshake&year=2021&month=4&day=4&name=wordpress'
   * 'events?categories=awards&locations=dublin&year=2021&month=4&day=4&show_all'
   *
   * @param array $params - $params have been checked for valid $params in routes.archived with queryParamsExists()
   *
   * This function will render the given data thru a twig template via render()
   *
   */
  public function queryMainIndexArchive($params) {
    
    /**
     *
     * Step 1 - get the global _context variables
     *
     */
    global $_context;
    
    /**
     *
     * Step 2 - reset the global _context variable for 'archive' to 'MainIndexArchive'
     *
     */
    $_context['archive'] = 'MainIndexArchive';
    
    /**
     *
     * Step 3 - parse the params string into an array (the params_array has been filtered for relevant ones only in routes)
     *
     */
    parse_str($params, $params_array);
    
    /**
     *
     * Step 4 - set the $params_array type based on $this->type. queryMainIndexArchive will have type impliciit thru class
     *
     */
    $params_array['type'] = typeSettingByKey('key', $this->type, 'single'); // must use the singular label here e.g: 'post'
    
    /**
     *
     * Step 5 - overwrite global _context values here relevant to the pagination
     * this is because we want valid query params to override the overall archive params when they exist in the query string
     *
     */
    
    // if type's 'per_page' setting exists, set global 'per_page' to it, else set global 'paged' to false (per_page will be 7 otherwise & paged will be true)
    if(typeSettingByKey('key', $this->type, 'per_page')) {
      $_context['per_page'] = typeSettingByKey('key', $this->type, 'per_page');
    }
    
    // else {
    //   $_context['per_page'] = null; // might as well set this to null? per_page shoould be okay at null if pagwed is false
    //   $_context['paged'] = false;
    // }
    
    /**
     *
     * Step 6 - set new params array values based on globals where relevant. for paged archives to work
     * we will try to keep the global _context & params_array values for pagination-things matching
     *
     */
    
    // if(isset($params_array['p'])) $_context['page'] = $params_array['p']; // if params_array HAS 'p', set global 'page' to it
    // if(isset($params_array['show_all'])) $_context['paged'] = false; // if params_array HAS 'show_all', set global 'paged' = false
    
    // if params has 'per_page' setting
    if(isset($params_array['per_page'])) {
      // $_context['per_page'] = $params_array['per_page']; // reset global 'per_page' to it
    } else {
      $params_array['per_page'] = $_context['per_page']; // else, set it to match the global (which is set according to type).
    }
    
    /**
     *
     * Step 7 - paramsArrayToString - turns params array back to a string with fixes (helpers.php)
     *
     */
    
    $new_params_string = paramsArrayToString($params_array);
    
    /**
     *
     * Step 7 - add the new params string to the global _context
     *
     */
    $_context['string_params'] = $new_params_string;
    
    /**
     *
     * Step 8 - get the archive object context from ArchiveModel -> getQueriedArchive() for twig to render
     *
     */
    $context['archive'] = (new ArchiveModel())->getQueriedArchive();
    
    /**
     *
     * Step 9 - add the global _content variables to the archive object context
     *
     */
    $context['context'] = $_context;
    
    /**
     *
     * Step 10 - render the context. if the data queried doesnt produce an archive title, i.e its not valid data or request, throw the error
     *
     */
    if(isset($context['archive']['title'])) {
      $this->render($context);
    } else {
      $this->error();
    }
  }
  
  /**
   *
   * Query the TaxTermArchive - This seems working. Double-check
   *
   * WORKING URLS
   * 'events/category/sport'
   * 'events/category/sport?show_all'
   * 'events/category/sport?per_page=3&p=2'
   * 'events/category/sport?locations=lisbon'
   * 'events/category/sport?locations=dublin&s=handshake&year=2021&month=4&day=4&name=wordpress'
   *
   * DO NOT WORK
   * 'events/category/sport?categories=awards' - querying term archives by additional terms of same taxonomy does not currently work. this could be made to work
   *
   * ERRORS
   * Non-valid params dont affect query at all, which is probably desirable. querying term archives by additional terms of same taxonomy is treated as non-valid. also probably desirable
   *
   * @param array $params - $params have been checked for valid $params in routes.archived with queryParamsExists()
   * @param string $tax - taxonomy string e.g: 'blog'
   * @param string $term - term string e.g: 'news'
   *
   * This function will render the given data thru a twig template via render()
   *
   */
  public function queryTaxTermArchive($params, $tax, $term) {
    
    /**
     *
     * Step 1 - get the global _context variables
     *
     */
    global $_context;
    
    /**
     *
     * Step 2 - reset the global _context variable for 'archive' to 'TaxTermArchive'
     *
     */
    $_context['archive'] = 'TaxTermArchive';
    
    /**
     *
     * Step 3 - parse the params string into an array (the params_array has been filtered for relevant ones only in routes)
     *
     */
    parse_str($params, $params_array);
    
    /**
     *
     * Step 4 - set the type & tax->term data here based on the given type, & tax & term given in routes
     *
     */
    $params_array['type'] = typeSettingByKey('key', $this->type, 'single');  // must use the singular label here e.g: 'post'
    $params_array[$tax] = $term;
    
    /**
     *
     * Step 5 - set the global tax & term values from the given tax & term
     *
     */
    $_context['tax'] = $tax;
    $_context['term'] = $term;
    
    /**
     *
     * Step 6 - overwrite global _context values here relevant to the pagination
     * this is because we want valid query params to override the overall archive params when they exist in the query string
     *
     */
    
    // if type's 'per_page' setting exists, set global 'per_page' to it, else set global 'paged' to false (per_page will be 7 otherwise & paged will be true)
    if(typeSettingByKey('key', $this->type, 'per_page')) {
      $_context['per_page'] = typeSettingByKey('key', $this->type, 'per_page'); // set global 'per_page' to it
    } else {
      $_context['per_page'] = null; // might as well set this to null? per_page shoould be okay at null if pagwed is false
      $_context['paged'] = false;
    }
    
    /**
     *
     * Step 7 - set new params array values based on globals where relevant. for paged archives to work
     * we will try to keep the global _context & params_array values for pagination-things matching
     *
     */

    if(isset($params_array['p'])) $_context['page'] = $params_array['p']; // if params_array HAS 'p', set global 'page' to it
    if(isset($params_array['show_all'])) $_context['paged'] = false; // if params_array HAS 'show_all', set global 'paged' = false

    // if params has 'per_page' setting
    if(isset($params_array['per_page'])) {
      $_context['per_page'] = $params_array['per_page']; // reset global 'per_page' to it
    } else {
      $params_array['per_page'] = $_context['per_page']; // else, set it to match the global
    }
    
    /**
     *
     * Step 8 - paramsArrayToString - turns params array back to a string with fixes (helpers.php)
     *
     */
    
    $new_params_string = paramsArrayToString($params_array);
    
    /**
     *
     * Step 9 - add the new params string to the global _context
     *
     */
    $_context['string_params'] = $new_params_string;
    
    /**
     *
     * Step 10 - get the archive object context from ArchiveModel -> getQueriedArchive() for twig to render
     *
     */
    $context['archive'] = (new ArchiveModel())->getQueriedArchive();
    
    /**
     *
     * Step 11 - add the global _content variables to the archive object context
     *
     */
    $context['context'] = $_context;
    
    /**
     *
     * Step 12 - render the context. if the data queried doesnt produce an archive title, i.e its not valid data or request, throw the error
     *
     */
    if(isset($context['archive']['title'])) {
      $this->render($context);
    } else {
      $this->error();
    }
  }

  /**
   *
   * Query the TaxCollectionArchive - This seems working. Double-check
   *
   * WORKING URLS
   * 'events/categories'
   * 'events/categories?show_all'
   * 'events/categories?per_page=1&p=2'
   * 'events/categories?order=asc&orderby=title'
   * 'events/categories?order=asc&orderby=title&per_page=1&p=2'
   *
   * DOES NOT WORK
   * 'events/categories?taxonomy=location'
   * querying tax archives by additional taxes does not work. the global $tax is already set & fed into the query string below. 
   * when querying tax archives with an additional 'taxonomy' param, the additional param will not affect the query. this is desirable
   * '?taxonomy=' query string parameter is designed mainly to allow for both string & array inputs into the QueryTermsModel. In terms of querying tax archives, 'taxonomy' in the string is a non-event
   * the params that will currently work to query tax archives are: 'orderby', 'order', 'per_page', 'p' & 'show_all'
   *
   * ERRORS
   * Non-valid params dont affect query at all, which is probably desirable. querying term archives by additional terms of same taxonomy is treated as non-valid. also probably desirable
   *
   * @param array $params - $params have been checked for valid $params in routes.archived with queryParamsExists()
   * @param string $tax - taxonomy key string e.g: 'blog'
   *
   * This function will render the given data thru a twig template via render()
   *
   */
  public function queryTaxCollectionArchive($params, $tax) {
    
    /**
     *
     * Step 1 - get the global _context variables
     *
     */
    global $_context;

    /**
     *
     * Step 2 - reset the global _context variable for 'archive' to 'TaxCollectionArchive'
     *
     */
    $_context['archive'] = 'TaxCollectionArchive';
    
    /**
     *
     * Step 3 - parse the params string into an array (the params_array has been filtered for relevant ones only in routes)
     *
     */
    parse_str($params, $params_array);
    
    /**
     *
     * Step 4 - set the params_array 'taxonomy' here based on the given tax
     *
     */
    $params_array['taxonomy'] = taxSettingByKey($this->type, 'key', $tax, 'single'); //  // must use the singular label here e.g: 'category'
    
    /**
     *
     * Step 5 - set the global tax value from the given tax
     *
     */
    $_context['tax'] = $tax;
    
    /**
     *
     * Step 6 - overwrite global _context values here relevant to the pagination
     * this is because we want valid query params to override the overall archive params when they exist in the query string
     *
     */
    
    // if type's 'per_page' setting exists, set global 'per_page' to it, else set global 'paged' to false (per_page will be 7 otherwise & paged will be true)
    if(typeSettingByKey('key', $this->type, 'per_page')) {
      $_context['per_page'] = typeSettingByKey('key', $this->type, 'per_page'); // set global 'per_page' to it
    } else {
      $_context['per_page'] = null; // might as well set this to null? per_page shoould be okay at null if pagwed is false
      $_context['paged'] = false;
    }
    
    /**
     *
     * Step 7 - set new params array values based on globals where relevant. for paged archives to work
     * we will try to keep the global _context & params_array values for pagination-things matching
     *
     */

    if(isset($params_array['p'])) $_context['page'] = $params_array['p']; // if params_array HAS 'p', set global 'page' to it
    if(isset($params_array['show_all'])) $_context['paged'] = false; // if params_array HAS 'show_all', set global 'paged' = false

    // if params has 'per_page' setting
    if(isset($params_array['per_page'])) {
      $_context['per_page'] = $params_array['per_page']; // reset global 'per_page' to it
    } else {
      $params_array['per_page'] = $_context['per_page']; // else, set it to match the global
    }
    
    /**
     *
     * Step 8 - paramsArrayToString - turns params array back to a string with fixes (helpers.php)
     *
     */
    $new_params_string = paramsArrayToString($params_array);
    
    /**
     *
     * Step 9 - add the new params string to the global _context
     *
     */
    $_context['string_params'] = $new_params_string;  
      
    /**
     *
     * Step 10 - get the archive object context from ArchiveModel -> getQueriedTaxonomyArchive() for twig to render
     *
     */
    $context['archive'] = (new ArchiveModel())->getQueriedTaxonomyArchive();
    
    /**
     *
     * Step 11 - add the global _content variables to the archive object context
     *
     */
    $context['context'] = $_context;
    
    /**
     *
     * Step 12 - render the context. if the data queried doesnt produce an archive title, i.e its not valid data or request, throw the error
     *
     */
    if(isset($context['archive']['title'])) {
      $this->render($context);
    } else {
      $this->error();
    }
  }
  
  /**
   *
   * Query the Site (like search queries) - Seems working
   *
   * WORKING URLS
   * '?type=post'
   * '?type=post&s=creativo'
   * '?type=event&per_page=2&p=1&s=creativo'
   * '?s=handshake&p=2'
   *
   * DO NOT WORK
   * '?type=post,project' - only one type works. for now
   * maybe can make work with mutiple types later. would require joining types together in QueryModel.
   *
   * @param array $params - $params have been checked for valid $params in routes.archived with queryParamsExists()
   *
   * This function will render the given data thru a twig template via render()
   *
   */
  public function querySite($params) {
    
    /**
     *
     * Step 1 - get the global _context variables
     *
     */
    global $_context;

    /**
     *
     * Step 2 - reset the global _context variable for 'archive' to 'SiteQuery'
     *
     */
    $_context['archive'] = 'SiteQuery';
    
    /**
     *
     * Step 3 - parse the params string into an array (the params_array has been filtered for relevant ones only in routes)
     *
     */
    parse_str($params, $params_array);

    // if(!isset($params_array['type'])) {
    //   $this->error();
    //   exit();
    // }
     
    /**
      *
      * Step 5 - overwrite global _context values here relevant to the pagination
      * this is because we want valid query params to override the overall archive params when they exist in the query string
      *
      */
      
      /**
       *
       * Step 7 - set new params array values based on globals where relevant. for paged archives to work
       * we will try to keep the global _context & params_array values for pagination-things matching
       *
       */
    
    if(isset($params_array['p'])) $_context['page'] = $params_array['p']; // if params_array HAS 'p', set global 'page' to it
    if(isset($params_array['show_all'])) $_context['paged'] = false; // if params_array HAS 'show_all', set global 'paged' = false
    
    // $new_per_page = $_context['per_page']; // starts out as the global per_page
    
    // if params array doesnt have a per_page, 
    if(!isset($params_array['per_page'])) {
      
      
      // but it does have a type & that type has its own per_page setting
      if(isset($params_array['type']) && typeSettingByKey('single', $params_array['type'], 'per_page')){
        $params_array['per_page'] = typeSettingByKey('single', $params_array['type'], 'per_page'); // set the new per_page to that
      }
      
      else {
        $params_array['per_page'] = $_context['per_page']; // starts out as the global per_page
      }
    }
    
    // // else if params array DOES have a per_page,
    // else {
    //   $new_per_page = $params_array['per_page']; // set the new per_page to that
    // }
    // 
    // $params_array['per_page'] = $new_per_page;
    
    // if global paged is set to false, we should add show_all to the params
    if($_context['paged'] == false){
      $params_array['show_all'] = ''; // add 'show_all' to the params
    }
    
    /**
     *
     * Step 8 - paramsArrayToString - turns params array back to a string with fixes (helpers.php)
     *
     */
    $new_params_string = paramsArrayToString($params_array);
    
    /**
     *
     * Step 9 - add the new params string to the global _context
     *
     */
    $_context['string_params'] = $new_params_string;  
    
    /**
     *
     * Step 10 - get the archive object context from ArchiveModel -> getQueriedArchive() for twig to render
     *
     */
    $context['archive'] = (new ArchiveModel())->getQueriedArchive();
    
    /**
     *
     * Step 11 - add the global _content variables to the archive object context
     *
     */
    $context['context'] = $_context;
    
    /**
     *
     * Step 12 - render the context. if the data queried doesnt produce an archive title, i.e its not valid data or request, throw the error
     *
     */
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
   *  This function will render the given data context a twig template via render() in Core
   *
   * @param object|array $context - the context for twig to render
   */
  protected function render($context) {
    
    // get the global _context variables
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