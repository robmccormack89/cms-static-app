<?php
namespace Rmcc;

class TaxonomyArchiveModel {
  
  /*
  *
  * This class is used to create taxonomy archive objects like categories or tags
  * Create a new TaxonomyArchiveModel object with $type, $tax, $paged, $page & $posts_per_page properties.
  * $type & $tax are required properties
  * The $archive property of the created object contains all the data for the archive item
  *
  */
  public function __construct($type, $tax, $paged, $page, $posts_per_page) {
    $this->type = $type;
    $this->tax = $tax;
    $this->paged = $paged;
    $this->page = $page;
    $this->posts_per_page = $posts_per_page;
  }
  
  // we need to get the archive object data in 3 parts: meta, posts & pagination
  public function _getTaxonomyArchive() {
    $archive = (new ArchiveMetaModel($this->type, $this->page, $this->tax))->meta;
    
    $terms_obj = new TaxonomyArchiveTermsModel($this->type, $this->tax, $this->paged, $this->page, $this->posts_per_page);
    $archive['posts'] = $terms_obj->terms;
    
    $paged_url = '/'.$this->type.'/'.$this->tax.'/page/';
    $archive['pagination'] = (new ArchivePaginationModel($this->posts_per_page, $this->page, $terms_obj->all_count, $this->paged, $paged_url))->pagination;
    
    // from getCollectionMeta()
    $title = ucfirst($this->tax);
    $archive['title'] = ($this->page > 1) ? $title.' (Page '.$this->page.')' : $title;
    
    return $archive;
  }
  
  // we need to get the archive object data in 3 parts: meta, posts & pagination
  public function getTaxonomyArchive() {
    $archive = (new QueriedObjectModel($this->type, $this->tax))->getQueriedObject();

  }
  
}