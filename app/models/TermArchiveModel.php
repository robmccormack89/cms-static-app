<?php
namespace Rmcc;

// for getting posts listings archives for given terms, like News (NOT collections!)
class TermArchiveModel {
  
  public function __construct($type, $tax, $term, $paged = false, $page = null, $posts_per_page = 4) {
    $this->type = $type;
    $this->tax = $tax;
    $this->term = $term;
    $this->paged = $paged;
    $this->page = $page;
    $this->posts_per_page = $posts_per_page;

    $this->archive = $this->getTermArchive();
  }
  
  public function getTermArchive() {
    $meta_obj = new MetaModel(
      $this->type, $this->page, $this->tax, $this->term
    );
    $term = $meta_obj->meta;
    
    $posts_obj = new PostsModel(
      $this->type, $this->paged, $this->page, $this->posts_per_page, $this->tax, $this->term
    );
    $term['posts'] = $posts_obj->posts;
    
    $url = '/'.$this->type.'/'.$this->tax.'/'.$this->term.'/page/';
    $pag_obj = new PaginationModel(
      $this->posts_per_page, $this->page, $posts_obj->all_count, $this->paged, $url
    );
    $term['pagination'] = $pag_obj->pagination;
    
    return $term;
  }
  
}