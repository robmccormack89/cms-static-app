<?php
namespace Rmcc;
use Nahid\JsonQ\Jsonq;

// for getting posts listings archives for given terms, like News (NOT collections!)
class TermArchiveModel extends ArchiveModel {
  
  public function __construct($type, $key, $tax, $term, $taxonomies, $paged, $page, $posts_per_page) {
    parent::__construct($type, $key, $taxonomies, $paged, $page, $posts_per_page);
    $this->tax = $tax;
    $this->term = $term;
  }
  
  public function getTermArchive() {
    
    $meta_obj = new MetaModel($this->type, $this->page, $this->tax, $this->term);
    $term = $meta_obj->meta;
    
    $posts_obj = new PostsModel($this->type, $this->key, $this->taxonomies, $this->paged, $this->page, $this->posts_per_page, $this->tax, $this->term);
    $term['posts'] = $posts_obj->posts;
    
    $url = $this->type.'/'.$this->tax.'/'.$this->term ;
    $pag_obj = new PaginationModel($this->posts_per_page, $this->page, $posts_obj->all_count, $this->paged, $url);
    $term['pagination'] = $pag_obj->pagination;

    // $term['pagination'] = [];
    
    return $term;
  }
  
}