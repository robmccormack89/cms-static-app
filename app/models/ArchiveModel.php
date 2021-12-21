<?php
namespace Rmcc;

class ArchiveModel {
  
  /**
   *
   * Archive functions
   * 
   * 3 types of archive for the app: MainIndexArchive(getArchive), TaxTermArchive(getTermArchive) & TaxCollectionArchive(getTaxonomyArchive)
   *
   * @return object|array - will return the archive object for rendering in ArchiveController
   *
   */
   
  public function getArchive() {
    
    // get the global $_context variables. these will be used to create the $args for the QueryModel
    global $_context;
    
    // 1. build the $args for the QueryModel using the global $_context variables
    $args = array(
      'type' => typeSettingByKey('key', $_context['type'], 'single'), // must use the singular label here e.g: 'post'
      'per_page' => $_context['per_page'],
      'p' => $_context['page'], // will be 1
      'show_all' => ($_context['paged']) ? false : true // if paged is false, set show_all to true
    );
    // get the posts object using the QueryModel
    $posts_obj = new QueryModel($args);
    
    // 2. Set the archive data; the meta data for the archive. We get the data from the $posts_obj->queried_object
    $archive = $posts_obj->queried_object;
    
    // 3. Set the archive posts data. We can get the data from the $posts_obj->posts
    $archive['posts'] = $posts_obj->posts;
    
    // 4. Set the archive pagination data. Only if there are posts tho
    if(!empty($archive['posts'])){
      
      // We use PaginationModel->getPagination to set the pagination data
      $archive['pagination'] = $posts_obj->pagination;
      
    }

    // 5. Finally, we return the newly-created archive object
    return $archive;
  }
  
  public function getTermArchive() {
    
    // get the global $_context variables. these will be used to create the $args for the QueryModel
    global $_context;
    
    // 1. build the $args for the QueryModel using the global $_context variables
    $args = array(
      'type' => typeSettingByKey('key', $_context['type'], 'single'), // must use the singular label here e.g: 'post'
      'tax_query' => array(
        // relation is required for now as there are checks based the iteration of the first array in this sequence, which should be 2nd when relation exists
        'relation' => 'AND', // working: 'AND', 'OR'. Deaults to 'AND'.
        array(
          'taxonomy' => taxSettingByKey($_context['type'], 'key', $_context['tax'], 'single'), // working. takes taxonomy string taxSettingByKey($type_archive, 'single', $value['taxonomy'], 'key');
          'terms' => array($_context['term']), // working. takes array
          'operator' => 'AND' // 'AND', 'IN', 'NOT IN'. 'IN' is default. AND means posts must have all terms in the array. In means just one.
        ),
      ),
      'per_page' => $_context['per_page'],
      'p' => $_context['page'], // will be 1
      'show_all' => ($_context['paged']) ? false : true // if paged is false, set show_all to true
    );
    // get the posts object using the QueryModel
    $posts_obj = new QueryModel($args);
    
    // 2. Set the archive data; the meta data for the archive. We get the data from the $posts_obj->queried_object
    $archive = $posts_obj->queried_object;
    
    // 3. Set the archive posts data. We can get the data from the $posts_obj->posts
    $archive['posts'] = $posts_obj->posts;
    
    // 4. Set the archive pagination data.
    if(!empty($archive['posts'])){
      
      // We use PaginationModel->getPagination to set the pagination data
      $archive['pagination'] = $posts_obj->pagination;
      
    }
    
    // 5. Finally, we return the newly-created archive object
    return $archive;
  }
  
  public function getTaxonomyArchive() {
  
    // get the global $_context variables. these will be used to create the $args for the QueryModel
    global $_context;
  
    // 1. build the $args for the QueryModel using the global $_context variables
    $args = array(
      'taxonomy' => taxSettingByKey($_context['type'], 'key', $_context['tax'], 'single'), // must use the singular label here e.g: 'category'
      'per_page' => $_context['per_page'],
      'p' => $_context['page'], // will be 1
      'show_all' => ($_context['paged']) ? false : true // if paged is false, set show_all to true
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
      $archive['pagination'] = (new PaginationModel($terms_obj->found_terms))->getPagination();
  
    }
  
    // 5. Finally, we return the newly-created archive object
    return $archive;
  }
  
  /**
   *
   * Queried Archive functions
   *
   * @return object|array - will return the archive object for rendering in ArchiveController
   *
   */
  
  public function getQueriedArchive() {
    
    // get the global $_context variables. these will be used to create the $args for the QueryModel
    global $_context;
    
    // 1. Use the QueryModel to get the posts object using the global _context $string_params (query methods in ArchiveController)
    $posts_obj = new QueryModel($_context['string_params']);
    
    // 2. Set the archive data; the meta data for the archive. We get the data from the $posts_obj->queried_object
    $archive = $posts_obj->queried_object;
    
    // 3. Set the archive posts data. We can get the data from the $posts_obj->posts
    $archive['posts'] = $posts_obj->posts;
    
    // 4. Set the archive pagination data. Only if there are posts tho
    if(!empty($archive['posts'])){
      
      // We use PaginationModel->getPagination to set the pagination data
      $archive['pagination'] = $posts_obj->pagination;
      
      // archives that have been paged require the archive title to be modified to reflect the paged page (queried archives only)
      if($_context['page'] > 1) $archive['title'] = $archive['title'].' (Page '.$_context['page'].')';
      
    }
    
    return $archive;
  }
  
  public function getQueriedTaxonomyArchive() {
  
    // get the global $_context variables. these will be used to create the $args for the QueryModel
    global $_context;
  
    // 1. Use the QueryTermsModel to get the terms object using the global _context $string_params (query methods in ArchiveController)
    $terms_obj = new QueryTermsModel($_context['string_params']);
  
    // 2. Set the archive data; the meta data for the archive. We get the data from the $terms_obj->queried_object
    $archive = $terms_obj->queried_object;
    
    // 3. Set the archive terms data. We can get the data from the $terms_obj->terms
    $archive['posts'] = $terms_obj->terms;
    
    // 4. Set the archive pagination data. Only if there are posts tho
    if(!empty($archive['posts'])){
      
      // We use PaginationModel->getPagination to set the pagination data
      $archive['pagination'] = $terms_obj->pagination;
      
      // archives that have been paged require the archive title to be modified to reflect the paged page (queried archives only)
      if($_context['page'] > 1) $archive['title'] = $archive['title'].' (Page '.$_context['page'].')';
      
    }
  
    return $archive;
  }
  
}