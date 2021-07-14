<?php
namespace Rmcc;

class TaxonomyArchiveModel {
  
  public function __construct($type, $tax, $paged = false, $page = null, $posts_per_page = 4) {
    $this->type = $type; // string. e.g 'blog' or 'portfolio'. required
    $this->tax = $tax;
    $this->paged = $paged; // boolean. setting. whether or not the archive is paged. defaults to false. optional
    $this->page = $page; // int. requested paginated page number. passed on via routes. defaults to null. optional
    $this->posts_per_page = $posts_per_page; // int. setting. how many posts to show per page on this archive. optional, defaults to 4
    
    $this->archive = $this->getTaxonomyArchive();
  }
  
  public function getTaxonomyArchive() {
    $meta_obj = new ArchiveMetaModel($this->type, $this->page, $this->tax);
    $collection = $meta_obj->meta;
    
    $terms_obj = new TaxonomyArchiveTermsModel($this->type, $this->tax, $this->paged, $this->page, $this->posts_per_page);
    $collection['posts'] = $terms_obj->terms;
    
    $url = '/'.$this->type.'/'.$this->tax.'/page/';
    $pag_obj = new ArchivePaginationModel($this->posts_per_page, $this->page, $terms_obj->all_count, $this->paged, $url);
    $collection['pagination'] = $pag_obj->pagination;
    
    return $collection;
  }
  
}