<?php
namespace Rmcc;

class ArchiveModel {
  
  public function __construct() {
    $this->page = 1;
    $this->per_page = 7;
    $this->paged = true;
    $this->init();
  }
  
  // reset __construct values depending whether $_context['type'] is set
  private function init() {
    
    global $_context;
    
    // if global _content 'type' is set first
    if(array_key_exists('type', $_context)){
      
      // if type's per_page is set
      if(typeSettingByKey('key', $_context['type'], 'per_page')) {
        $this->per_page = typeSettingByKey('key', $_context['type'], 'per_page');
      }
      
      // else if type's per_page isnt set
      else {
        $this->paged = false;
        $this->per_page = null;
      }
      
    }
    
  }
  
  /**
   *
   * Standard (non-queried) Archive functions
   *
   */
   
  public function getMainArchive() {
    
    // 1. Get the global _context variables
    global $_context;
    
    // 2. Build the $args array for the QueryModel to process
    $args = array(
      'type' => typeSettingByKey('key', $_context['type'], 'single'),
      'per_page' => $this->per_page,
      'p' => $this->page,
      'show_all' => ($this->paged) ? false : true
    );
    $posts_obj = new QueryModel($args);
    
    // 3. Set the archive data. queried_object will hold meta data on the request, posts will hold the posts array to be looped over
    $archive = $posts_obj->queried_object;
    $archive['posts'] = $posts_obj->posts;
    
    // 4. If archive.posts has some posts, set archive.pagination from $posts_obj->pagination
    if(!empty($archive['posts'])){
      $archive['pagination'] = $posts_obj->pagination;
    }

    // 5. Finally, we return the archive data
    return $archive;
  }
  
  public function getTermArchive() {
    
    // 1. Get the global _context variables
    global $_context;
    
    // 2. Build the $args array for the QueryModel to process
    $args = array(
      'type' => typeSettingByKey('key', $_context['type'], 'single'),
      'tax_query' => array(
        'relation' => 'AND',
        array(
          'taxonomy' => taxSettingByKey($_context['type'], 'key', $_context['tax'], 'single'),
          'terms' => array($_context['term']),
          'operator' => 'AND'
        ),
      ),
      'per_page' => $this->per_page,
      'p' => $this->page,
      'show_all' => ($this->paged) ? false : true
    );
    $posts_obj = new QueryModel($args);
    
    // 3. Set the archive data. queried_object will hold meta data on the request, posts will hold the posts array to be looped over
    $archive = $posts_obj->queried_object;
    $archive['posts'] = $posts_obj->posts;
    
    // 4. If archive.posts has some posts, set archive.pagination from $posts_obj->pagination
    if(!empty($archive['posts'])){
      $archive['pagination'] = $posts_obj->pagination;
    }

    // 5. Finally, we return the archive data
    return $archive;
  }
  
  public function getTaxonomyArchive() {
  
    // 1. Get the global _context variables 
    global $_context;
  
    // 2. Build the $args array for the QueryModel to process
    $args = array(
      'taxonomy' => taxSettingByKey($_context['type'], 'key', $_context['tax'], 'single'), // must use the singular label here e.g: 'category'
      'per_page' => $this->per_page,
      'p' => $this->page,
      'show_all' => ($this->paged) ? false : true // if paged is false, set show_all to true
    );
    $terms_obj = new QueryTermsModel($args);
  
    // 3. Set the archive data. queried_object will hold meta data on the request, posts will hold the posts array to be looped over
    $archive = $terms_obj->queried_object;
    $archive['posts'] = $terms_obj->terms;
  
    // 4. If archive.posts has some posts, set archive.pagination from $posts_obj->pagination
    if(!empty($archive['posts'])){
      $archive['pagination'] = $terms_obj->pagination;
    }
  
    // 5. Finally, we return the archive data
    return $archive;
  }
  
  /**
   *
   * Query Archive functions
   *
   */
  
  // ArchiveController()->querySite, ArchiveController()->queryMainIndexArchive & ArchiveController()->queryTaxTermArchive
  public function getQueriedArchive($params) {
     
    /**
     *
     * 1. Get the global _context variables
     *
     */
     
    global $_context;

    /**
     *
     * 2. parse the $params string into an array, then modify it, before turning it back into a string to input into QueryModel
     *
     */

    // parse the $params string into an array
    parse_str($params, $params_array);

    // if global _context 'type' is set & it produces a 'single' label.. 
    if(array_key_exists('type', $_context) && typeSettingByKey('key', $_context['type'], 'single')){
      $params_array['type'] = typeSettingByKey('key', $_context['type'], 'single');
    }
    
    // if global _context 'tax' & 'term' are set... 
    if(array_key_exists('tax', $_context) && array_key_exists('term', $_context)){
      $params_array[$_context['tax']] = $_context['term'];
    }
    
    // if params_array DOESNT HAVE a 'per_page' param
    if(!isset($params_array['per_page'])) {
      
      // if params_array DOESNT HAVE 'per_page' but has 'type'
      if(isset($params_array['type'])){
        
        // if params_array 'type' param HAS a 'per_page' setting in config
        if(typeSettingByKey('single', $params_array['type'], 'per_page')) {
          $params_array['per_page'] = typeSettingByKey('single', $params_array['type'], 'per_page'); // set params_array 'per_page' to it
        }
        
        // else if it DOESNT HAVE a 'per_page' setting
        else {
          $params_array['show_all'] = ''; // add 'show_all' to params_array
        }
        
      }
      
      // else if params_array DOESNT HAVE both 'per_page' & 'type' params
      else {
        $params_array['per_page'] = $this->per_page; // params_array 'per_page' will then default to the class default of 7
      }
      
    }
    
    // if params_array DOESNT HAVE 'p' param
    if(!isset($params_array['p'])) {
      $params_array['p'] = $this->page; // should be class default of 1
    }
    
    // if params_array HAS 'show_all' param & params_array 'p' is greater than 1
    if(isset($params_array['show_all']) && $params_array['p'] > 1){
      $instance = new CoreController(); // we fire error() here then bail as paged pages shouldnt work when showing all posts
      $instance->error(); // otherwise, queries like '?show_all&p=2' will show results & be wrong (wrong pagination etc)
      exit(); // so best to throw the error in these situations
    }
    
    // take the modified params array & turn it back into a string via paramsArrayToString()
    $params_string = paramsArrayToString($params_array);
    
    /**
     *
     * 3. Pass the new string onto the QueryModel
     *
     */

    $posts_obj = new QueryModel($params_string);
    
    /**
     *
     * 4. Set the archive data now
     *
     * $posts_obj->queried_object will hold meta data on the request
     * $posts_obj->posts will hold the posts array to be looped over
     *
     */
    
    $archive = $posts_obj->queried_object;
    $archive['posts'] = $posts_obj->posts;
    
    /**
     *
     * 5. If archive.posts has some posts, set archive.pagination from $posts_obj->pagination
     * also modifiy the archive title to reflect the pagination
     *
     */
    if(!empty($archive['posts'])) {
      $archive['pagination'] = $posts_obj->pagination;
      if($params_array['p'] > 1) $archive['title'] = $archive['title'].' (Page '.$params_array['p'].')';
    }
    
    /**
     *
     * 6. Finally, return the archive data
     *
     */
    return $archive;
  }
  
  // ArchiveController()->queryTaxCollectionArchive
  public function getQueriedTaxonomyArchive($params) {
  
    /**
     *
     * 1. Get the global _context variables
     *
     */
  
    global $_context;
    
    /**
     *
     * 2. parse the $params string into an array, then modify it, before turning it back into a string to input into QueryModel
     *
     */
    
    parse_str($params, $params_array);
    
    // if global _context 'type' is set
    if(array_key_exists('type', $_context)){
      
      // if it produces a 'single' label.. 
      if(typeSettingByKey('key', $_context['type'], 'single')) {
        $params_array['type'] = typeSettingByKey('key', $_context['type'], 'single'); // set params_array 'type' to it
      }
      
        // if it produces a 'per_page' setting.. 
      if(typeSettingByKey('key', $_context['type'], 'per_page')) {
        $this->per_page  = typeSettingByKey('key', $_context['type'], 'per_page'); // set $this->per_page to it (to be used below)
      }
      
      // if global _context 'tax' is set & it produces a 'single' label.. 
      if(array_key_exists('tax', $_context) && taxSettingByKey($_context['type'], 'key', $_context['tax'], 'single')){
        $params_array['taxonomy'] = taxSettingByKey($_context['type'], 'key', $_context['tax'], 'single'); // set params_array 'taxonomy' to it
      }
      
    }
    
    // if params_array 'per_page' ISNT set, set it now
    if(!isset($params_array['per_page'])) {
      $params_array['per_page'] = $this->per_page; // $this->per_page depends on conditions above & then init() before that. the class default is 7 before any conditions
    }
    
    // if params_array 'p' ISNT set, set it now
    if(!isset($params_array['p'])) {
      $params_array['p'] = $this->page; // should be 1, the class default
    }
    
    $params_string = paramsArrayToString($params_array);
    
    /**
     *
     * 3. Pass the new string onto the QueryModel
     *
     */
  
    $terms_obj = new QueryTermsModel($params_string);
    
    /**
     *
     * 4. Set the archive data now
     *
     * $terms_obj->queried_object will hold meta data on the request
     * $terms_obj->terms will hold the terms array to be looped over
     *
     */
  
    $archive = $terms_obj->queried_object;
    $archive['posts'] = $terms_obj->terms;
    
    /**
     *
     * 5. If archive.posts has some posts, set archive.pagination from $posts_obj->pagination
     * also modifiy the archive title to reflect the pagination
     *
     */
    if(!empty($archive['posts'])){
      $archive['pagination'] = $terms_obj->pagination;
      if($params_array['p'] > 1) $archive['title'] = $archive['title'].' (Page '.$params_array['p'].')';
    }
  
    /**
     *
     * 6. Finally, return the archive data
     *
     */
    return $archive;
  }
  
}