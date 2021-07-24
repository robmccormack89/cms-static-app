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
  public function __construct(string $type, string $tax, bool $paged = false, int $page = null, int $posts_per_page = 4) {
    $this->type = $type; // e.g 'blog' or 'portfolio'. required
    $this->tax = $tax; // e.g 'categories' or 'tags'. required
    $this->paged = $paged; // true or false. set whether this archive should be paged
    $this->page = $page; // if archive is paged, this would be the requested page e.g 2, 3 or 4 etc
    $this->posts_per_page = $posts_per_page; // how many items to display per page, defaults to 4
    
    $this->archive = $this->getTaxonomyArchive(); // this property then contains all our data
  }
  
  // we need to get the archive object data in 3 parts: meta, posts & pagination
  public function getTaxonomyArchive() {
    $archive = (new ArchiveMetaModel($this->type, $this->page, $this->tax))->meta;
    
    $terms_obj = new TaxonomyArchiveTermsModel($this->type, $this->tax, $this->paged, $this->page, $this->posts_per_page);
    $archive['posts'] = $terms_obj->terms;
    
    $paged_url = '/'.$this->type.'/'.$this->tax.'/page/';
    $archive['pagination'] = (new ArchivePaginationModel($this->posts_per_page, $this->page, $terms_obj->all_count, $this->paged, $paged_url))->pagination;
    
    return $archive;
  }
  
}