<?php
namespace Rmcc;
use Nahid\JsonQ\Jsonq;

class TermsModel {
  
  // class for setting the tease data in terms lists (collections!)
  public function __construct($type, $tax, $taxonomies, $paged, $page, $posts_per_page) {
    $this->type = $type;
    $this->tax = $tax;
    $this->taxonomies = $taxonomies;
    $this->paged = $paged;
    $this->page = $page;
    $this->posts_per_page = $posts_per_page;
    $this->terms = $this->getCollectionTerms();
    $this->count = $this->getAllTermsCount();
  }
  
  private function getCollectionTerms() {
    
    $data = $this->getAllTerms();
  
    // if paged is true
    if ($this->paged){
      $q = new Jsonq('../public/json/data.min.json');
      $terms = $q->from('site.content_types.'.$this->type.'.taxonomies.'.$this->tax)
      ->chunk($this->posts_per_page);
      if($terms){
        $data = $this->getPagedTerms($terms);
      } else {
        $data = null;
      }
    } 
  
    return $data;
  }
  private function getAllTermsCount() {
    $q = new Jsonq('../public/json/data.min.json');
    $posts = $q->from('site.content_types.'.$this->type.'.taxonomies.'.$this->tax)
    ->get();
    $data = $posts->count();
  
    return $data;
  }
  private function getAllTerms() {
    $q = new Jsonq('../public/json/data.min.json');
    $posts = $q->from('site.content_types.'.$this->type.'.taxonomies.'.$this->tax)
    ->get();
    
    $data = $this->setTermsTease($posts);
    
    return $data;
  }
  
  // get the paged terms
  private function getPagedTerms($terms) {
    
    $data = false;

    if (!$this->page) {
      $offset = 0;
    } else {
      $offset = $this->page - 1;
    }

    if (!($this->posts_per_page * $offset >= $this->getAllTermsCount())) {
      $data = $this->setTermsTease($terms[$offset]);
    }
    
    return $data;
  }
  
  // setting the Post's Tease data
  private function setTermsTease($terms) {
    $linked_terms = $this->setTermsTeaseLink($terms);
    return $linked_terms;
  }
  // setting the Post's Tease Link data
  private function setTermsTeaseLink($terms) {
    foreach ($terms as $term) {
      $term['link'] = '/'.$this->type.'/'.$this->tax.'/'.$term['slug'];
      $data[] = $term;
    }
    return $data;
  }
  
}