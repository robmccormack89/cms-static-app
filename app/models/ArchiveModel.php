<?php
namespace Rmcc;

class ArchiveModel {
  
  public function __construct($type, $paged, $page, $posts_per_page) {
    $this->type = $type;
    $this->paged = $paged;
    $this->page = $page;
    $this->posts_per_page = $posts_per_page;
  }

  public function getArchive() {

    /*
    *
    * 1. Use the QueryModel to get the posts object using the $args
    * The $args are made up from ArchiveModel properties
    *
    */
    $args = array(
      'type' => type_setting_by_key('key', $this->type, 'single'),
      'per_page' => $this->posts_per_page,
      'p' => $this->page,
      'show_all' => ($this->paged) ? false : true
    );
    $posts_obj = new QueryModel($args);
    
    /*
    *
    * 2. Set the archive data; the meta data for the archive
    * We can get the data from the $posts_obj->queried_object
    * Also, if the requested paged page is greater than 1, we modify the archive title to reflect the paged page
    *
    */
    $archive = $posts_obj->queried_object;
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
    $paged_url = $GLOBALS['config']['types'][$this->type]['index_uri'].'/page/';
    $archive['pagination'] = (new PaginationModel($this->posts_per_page, $this->page, $posts_obj->found_posts, $paged_url))->getPagination();

    return $archive;
  }
  
}