<?php
namespace Rmcc;

class CollectionArchiveModel extends TermArchivemodel {
  
  public function __construct($type, $tax, $paged, $page, $posts_per_page) {
    parent::__construct($type, '', $tax, '', '', $paged, $page, $posts_per_page);
  }
  
  public function getCollectionArchiveTest() {
    echo($this->tax);
  }
  
  public function getCollectionArchive() {
    
    $meta_obj = new MetaModel($this->type, $this->page, $this->tax);
    $term = $meta_obj->meta;
    
    $terms_obj = new TermsModel($this->type, $this->tax, $this->paged, $this->page, $this->posts_per_page);
    $term['posts'] = $terms_obj->terms;
    // $term['posts'] = [1];
    
    $url = $this->type.'/'.$this->tax;
    $pag_obj = new PaginationModel($this->posts_per_page, $this->page, $terms_obj->all_count, $this->paged, $url);
    $term['pagination'] = $pag_obj->pagination;
    
    return $term;
  }
  
}