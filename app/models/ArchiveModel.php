<?php
namespace Rmcc;

class ArchiveModel {
  
  public function __construct() {
    $this->page = 1;
    $this->per_page = 7;
    $this->paged = true;
  }
  
  /**
   *
   * Standard (non-queried) Archive functions
   *
   */
   
  public function getMainArchive() {
    
    global $_context;
    
    if(typeSettingByKey('key', $_context['type'], 'per_page')) {
      $this->per_page = typeSettingByKey('key', $_context['type'], 'per_page');
    } else {
      $this->paged = false;
      $this->per_page = null;
    }
    
    $args = array(
      'type' => typeSettingByKey('key', $_context['type'], 'single'),
      'per_page' => $this->per_page,
      'p' => $this->page,
      'show_all' => ($this->paged) ? false : true
    );
    
    $posts_obj = new QueryModel($args);
    
    $archive = $posts_obj->queried_object;
    
    $archive['posts'] = $posts_obj->posts;
    
    if(!empty($archive['posts'])){
      $archive['pagination'] = $posts_obj->pagination;
    }

    return $archive;
  }
  
  public function getTermArchive() {
    
    global $_context;
    
    if(typeSettingByKey('key', $_context['type'], 'per_page')) {
      $this->per_page = typeSettingByKey('key', $_context['type'], 'per_page');
    } else {
      $this->paged = false;
      $this->per_page = null;
    }

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
    
    $archive = $posts_obj->queried_object;

    $archive['posts'] = $posts_obj->posts;
    
    if(!empty($archive['posts'])){
      $archive['pagination'] = $posts_obj->pagination;
    }

    return $archive;
  }
  
  public function getTaxonomyArchive() {
  
    // get the global $_context variables. these will be used to create the $args for the QueryModel
    global $_context;
    
    // if type's 'per_page' setting exists, set 'per_page' to it
    if(typeSettingByKey('key', $_context['type'], 'per_page')) {
      $this->per_page = typeSettingByKey('key', $_context['type'], 'per_page');
    } else {
      $this->paged = false; // else paged is false
      $this->per_page = null; // and et per_page to null
    }
  
    // 1. build the $args for the QueryModel using the global $_context variables
    $args = array(
      'taxonomy' => taxSettingByKey($_context['type'], 'key', $_context['tax'], 'single'), // must use the singular label here e.g: 'category'
      'per_page' => $this->per_page,
      'p' => $this->page,
      'show_all' => ($this->paged) ? false : true // if paged is false, set show_all to true
    );
    // get the terms object using the QueryModel
    $terms_obj = new QueryTermsModel($args);
  
    // 2. Set the archive data; the meta data for the archive. We get the data from the $terms_obj->queried_object
    $archive = $terms_obj->queried_object;
  
    // 3. Set the archive posts data. We get the data from the $terms_obj->terms
    $archive['posts'] = $terms_obj->terms;
  
    // 4. Set the archive pagination data.
    if(!empty($archive['posts'])){
  
      // We use PaginationModel->getPagination to set the pagination data
      $archive['pagination'] = $terms_obj->pagination;
  
    }
  
    // 5. Finally, we return the newly-created archive object
    return $archive;
  }
  
  /**
   *
   * Query Archive functions
   *
   */
  
  public function getQueriedArchive($params) {
    
    global $_context;

    parse_str($params, $params_array);

    if(array_key_exists('type', $_context) && typeSettingByKey('key', $_context['type'], 'single')){
      $params_array['type'] = typeSettingByKey('key', $_context['type'], 'single');
    }
    
    if(array_key_exists('tax', $_context) && array_key_exists('term', $_context)){
      $params_array[$_context['tax']] = $_context['term'];
    }
    
    if(array_key_exists('type', $_context) && typeSettingByKey('key', $_context['type'], 'per_page')) {
      $this->per_page  = typeSettingByKey('key', $_context['type'], 'per_page');
    }
    
    if(!isset($params_array['per_page'])) {
      
      if(isset($params_array['type']) && typeSettingByKey('single', $params_array['type'], 'per_page')){
        $params_array['per_page'] = typeSettingByKey('single', $params_array['type'], 'per_page');
      }
      
      elseif(isset($params_array['type']) && !typeSettingByKey('single', $params_array['type'], 'per_page')) {
        $params_array['show_all'] = '';
      }
      
      else {
        $params_array['per_page'] = $this->per_page;
      }
      
    }
    
    if(!isset($params_array['p'])) {
      $params_array['p'] = $this->page;
    }
    
    if(isset($params_array['show_all']) && $params_array['p'] > 1){
      $instance = new CoreController();
      $instance->error();
      exit();
    }
    
    $params_string = paramsArrayToString($params_array);

    $posts_obj = new QueryModel($params_string);
    
    $archive = $posts_obj->queried_object;
    
    $archive['posts'] = $posts_obj->posts;
    
    if(!empty($archive['posts'])) {
      $archive['pagination'] = $posts_obj->pagination;
      if($params_array['p'] > 1) $archive['title'] = $archive['title'].' (Page '.$params_array['p'].')';
    }
    
    return $archive;
  }
  
  public function getQueriedTaxonomyArchive($params) {
  
    global $_context;
    
    parse_str($params, $params_array);
    
    if(array_key_exists('type', $_context) && typeSettingByKey('key', $_context['type'], 'single')){
      $params_array['type'] = typeSettingByKey('key', $_context['type'], 'single');
    }
    
    $params_array['taxonomy'] = taxSettingByKey($_context['type'], 'key', $_context['tax'], 'single');
    
    if(array_key_exists('type', $_context) && typeSettingByKey('key', $_context['type'], 'per_page')) {
      $this->per_page  = typeSettingByKey('key', $_context['type'], 'per_page');
    }
    
    if(!isset($params_array['per_page'])) {
      $params_array['per_page'] = $this->per_page;
    }
    
    if(!isset($params_array['p'])) {
      $params_array['p'] = $this->page;
    }
    
    $params_string = paramsArrayToString($params_array);
  
    $terms_obj = new QueryTermsModel($params_string);
  
    $archive = $terms_obj->queried_object;
    
    $archive['posts'] = $terms_obj->terms;
    
    if(!empty($archive['posts'])){
      $archive['pagination'] = $terms_obj->pagination;
      if($params_array['p'] > 1) $archive['title'] = $archive['title'].' (Page '.$params_array['p'].')';
    }
  
    return $archive;
  }
  
}