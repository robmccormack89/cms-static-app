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
    
    $archive = (new ArchiveMetaModel($this->type, $this->page))->meta;
    
    // $posts_obj = new ArchivePostsModel($this->type, $this->paged, $this->page, $this->posts_per_page);
    // $archive['posts'] = $posts_obj->posts;
    
    $_type = type_setting_by_key('key', $this->type, 'single'); // returns 'blog' or 'portfolio' etc...
    $_p = (!$this->page) ? 1 : $this->page;
    $show_all = ($this->paged) ? false : true;
    $args = array(
      'type' => $_type,
      'per_page' => $this->posts_per_page,
      'p' => $_p,
      'show_all' => $show_all
    );
    $posts_obj = new QueryModel($args);
    $posts = $posts_obj->posts;
    $count = $posts_obj->found_posts;
    $archive['posts'] = $posts;
    
    $paged_url = $GLOBALS['config']['types'][$this->type]['index_uri'].'/page/';
    $archive['pagination'] = (new ArchivePaginationModel($this->posts_per_page, $this->page, $count, $this->paged, $paged_url))->pagination;
    
    return $archive;
  }
  
}