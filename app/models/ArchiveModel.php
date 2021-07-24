<?php
namespace Rmcc;

class ArchiveModel {
  
  /*
  *
  * This class is used to create MainIndexArchive objects for content types like 'blog' or 'portfolio'
  * Create a new ArchiveModel object with $type, $paged, $page & $posts_per_page properties.
  * $type is a required property
  * The $archive property of the created object contains all the data for the archive item
  *
  */
  public function __construct(
    string $type, // e.g 'blog' or 'portfolio'. required
    bool $paged = false, // true or false. set whether this archive should be paged
    int $page = null, // if archive is paged, this would be the requested page (e.g 2, 3 or 4)
    int $posts_per_page = 4 // how many items to display per page
  ) 
  {
    $this->type = $type;
    $this->paged = $paged;
    $this->page = $page;
    $this->posts_per_page = $posts_per_page;
  }

  // we need to get the archive object data in 3 parts: meta, posts & pagination
  public function getArchive() {
    
    $args = array(
      'type' => type_setting_by_key('key', $this->type, 'single'),
      'per_page' => $this->posts_per_page,
      'p' => (!$this->page) ? 1 : $this->page,
      'show_all' => ($this->paged) ? false : true
    );
    $posts_obj = new QueryModel($args);
    
    $archive = $posts_obj->queried_object;
    $archive['posts'] = $posts_obj->posts;
    
    $archive['pagination'] = (new PaginationModel(
      $this->posts_per_page, 
      $this->page,
      $posts_obj->found_posts, 
      $this->paged, 
      $GLOBALS['config']['types'][$this->type]['index_uri'].'/page/'
    ))->pagination;
    
    return $archive;
  }
  
}