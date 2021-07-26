<?php
namespace Rmcc;

class TermArchiveModel {
  
  public function __construct($type, $tax, $term, $paged, $page, $posts_per_page) {
    $this->type = $type;
    $this->tax = $tax;
    $this->term = $term;
    $this->paged = $paged;
    $this->page = $page;
    $this->posts_per_page = $posts_per_page;
  }
  
  public function getTermArchive() {
    
    /*
    *
    * 1. Use the QueryModel to get the posts object using the $args
    * The $args are made up from TermArchiveModel properties
    *
    */
    $args = array(
      'type' => type_setting_by_key('key', $this->type, 'single'),
      'tax_query' => array(
        // relation is required for now as there are checks based the iteration of the first array in this sequence, which should be 2nd when relation exists
        'relation' => 'AND', // working: 'AND', 'OR'. Deaults to 'AND'.
        array(
          'taxonomy' => tax_setting_by_key($this->type, 'key', $this->tax, 'single'), // working. takes taxonomy string tax_setting_by_key($type_archive, 'single', $value['taxonomy'], 'key');
          'terms' => array($this->term), // working. takes array
          'operator' => 'AND' // 'AND', 'IN', 'NOT IN'. 'IN' is default. AND means posts must have all terms in the array. In means just one.
        ),
      ),
      'per_page' => $this->posts_per_page,
      'p' => $this->page,
      'show_all' => ($this->paged) ? false : true
    );
    $posts_obj = new QueryModel($args);
    
    /*
    *
    * 2. Set the archive data; the meta data for the archive
    * We get the data from QueriedObjectModel->getQueriedObject()
    * Also, if the requested paged page is greater than 1, we modify the archive title to reflect the paged page
    *
    * We may want to get the data using $posts_obj->queried_object instead. QueryModel needs to be modified to do this
    *
    */
    $archive = (new QueriedObjectModel($this->type, $this->tax, $this->term))->getQueriedObject();
    if($this->page > 1) $archive['title'] = $archive['title'].' (Page '.$this->page.')';
    
    /*
    *
    * 3. Set the archive posts data
    * We can get the data from the $posts_obj->posts
    *
    */
    $archive['posts'] = $posts_obj->posts;
    
    /*
    *
    * 4. Set the archive pagination data
    * We use a new PaginationModel->getPagination() object to set the pagination data
    *
    * This may be incorporated into QueryModel as a returned property like $posts_obj->pagination
    *
    */
    $paged_url = '/'.$this->type.'/'.$this->tax.'/'.$this->term.'/page/';
    $archive['pagination'] = (new PaginationModel($this->posts_per_page, $this->page, $posts_obj->found_posts, $paged_url))->getPagination();
    
    return $archive;
  }
  
}