<?php
namespace Rmcc;

// the model for getting data for a main index archive such as 'blog' or 'portfolio'
class ArchiveModel {
  
  public function __construct(string $type, bool $paged = false, int $page = null, int $posts_per_page = 4) {
    $this->type = $type; // e.g 'blog' or 'portfolio'. required
    $this->paged = $paged; // true or false. set whether this archive should be paged
    $this->page = $page; // if archive is paged, this would be the requested page e.g 2, 3 or 4 etc
    $this->posts_per_page = $posts_per_page; // how many items to display per page, defaults to 4
    
    $this->archive = $this->getArchive();
  }

  // get the archive object data in 3 parts: meta, posts & pagination
  public function getArchive() {
    $archive = (new ArchiveMetaModel($this->type, $this->page))->meta;
    
    $posts_obj = new ArchivePostsModel($this->type, $this->paged, $this->page, $this->posts_per_page);
    $archive['posts'] = $posts_obj->posts;
    
    $paged_url = $GLOBALS['config']['types'][$this->type]['index_uri'].'/page/';
    $archive['pagination'] = (new ArchivePaginationModel($this->posts_per_page, $this->page, $posts_obj->all_count, $this->paged, $paged_url))->pagination;
    
    return $archive;
  }
  
}