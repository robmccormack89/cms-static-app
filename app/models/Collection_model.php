<?php
// https://github.com/nahid/jsonq
use Nahid\JsonQ\Jsonq;

class Collection_model extends Term_model {
  
  public function __construct($type, $page, $tax) {
    parent::__construct($type, $page, $tax, null);
  }
  
  public function get_cat_collection() {
  
    $data = $this->get_archive_meta();
    $data['posts'] = $this->get_collection_terms();
    $data['pagination'] = $this->get_archive_pagination();

    return $data;
  } 
  
  public function get_collection_terms() {
    
    if($this->tax == 'categories' || $this->tax == 'tags') {
      $pre = 'site.blog.taxonomies.';
      $q = new Jsonq('../public/json/data.min.json');
      $terms = $q->from($pre.$this->tax)->chunk($this->get_archive_meta_pagination()['posts_per_page']);
    } 
      
    if ($this->get_archive_meta_pagination()['is_paged']) {
        
      if($this->get_paged_collection_terms($terms)) {
        $data = $this->get_paged_collection_terms($terms);
      } else {
        $data = false;
      }
      
    } else {
      $data = $this->get_all_terms();
    }
    
    return $data;
  }
  
  public function get_paged_collection_terms($terms) {
    if (!$this->page) {
      $page = 0;
    } else {
      $page = $this->page - 1;
    }
      
    if (!($this->get_archive_meta_pagination()['posts_per_page'] * $page >= $this->get_all_collection_terms()->count)) {
      $data = generate_tease_term_links($terms[$page], $this->get_archive_routes()['category_url']);
    } else {
      $data = false;
    }
    
    return $data;
  }
  
  public function get_all_terms() {
    // turn json object to php array
    $posts = json_decode( $this->get_all_collection_terms()->posts, TRUE );
    $data = generate_tease_post_links($posts, $this->get_archive_routes()['category_url']);
    return $data;
  }
  
  public function get_all_collection_terms() {
    if (!isset($data)) $data = new stdClass();
    
    if($this->tax == 'categories' || $this->tax == 'tags') {
      $pre = 'site.blog.taxonomies.';
      $q = new Jsonq('../public/json/data.min.json');
      $data->posts = $q->from($pre.$this->tax)->get();
      $data->count = $data->posts->count();
    } 
    
    return $data;
  }
  
}