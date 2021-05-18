<?php
namespace Rmcc;

// the model for getting data for a main index archive such as 'blog' or 'portfolio'
class ArchiveModel {
  
  public function __construct($type, $paged = false, $page = null, $posts_per_page = 4) {
    $this->type = $type; // string. e.g 'blog' or 'portfolio'. required
    $this->paged = $paged; // boolean. setting. whether or not the archive is paged. defaults to false. optional
    $this->page = $page; // int. requested paginated page number. passed on via routes. defaults to null. optional
    $this->posts_per_page = $posts_per_page; // int. setting. how many posts to show per page on this archive. optional, defaults to 4
    
    $this->archive = $this->getArchive();
  }

  // get the actual archive data in 3 parts: meta, posts & pagination
  public function getArchive() {
    $meta_obj = new MetaModel($this->type, $this->page);
    $archive = $meta_obj->meta;
    
    $posts_obj = new PostsModel($this->type, $this->paged, $this->page, $this->posts_per_page);
    $archive['posts'] = $posts_obj->posts;
    
    $pag_obj = new PaginationModel(
      $this->posts_per_page, $this->page, $posts_obj->all_count, $this->paged, $GLOBALS['config']['types'][$this->type]['index_uri']
    );
    $archive['pagination'] = $pag_obj->pagination;
    
    return $archive;
  }
  
}