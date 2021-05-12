<?php
// https://github.com/nahid/jsonq
use Nahid\JsonQ\Jsonq;

class Collection_model extends Term_model {
  
  public function __construct($type, $page, $tax) {
    parent::__construct($type, $page, $tax, null);
  }
  
  public function get_collection() {
    
    $collection = $this->get_collection_meta();
    $collection['posts'] = $this->get_posts();
    $collection['pagination'] = $this->get_pagination();

    return $collection;
  } 
  
  public function get_collection_meta() {
    $data = $this->get_meta();
    
    $data['title'] = get_collection_title($this->tax, $this->page);
    
    return $data;
  }
  public function get_posts() {
    $q = new Jsonq('../public/json/data.min.json');
    $terms = $q->from('site.'.$this->type.'.taxonomies.'.$this->tax)
    ->chunk($this->get_type_settings($this->type)['posts_per_page']);
      
    if ($this->get_type_settings($this->type)['is_paged']) {
        
      if($this->get_paged_posts($terms)) {
        $data = $this->get_paged_posts($terms);
      } else {
        $data = false;
      }
      
    } else {
      $data = $this->get_all_posts();
    }
    
    return $data;
  }
  public function get_all_posts() {
    $q = new Jsonq('../public/json/data.min.json');
    $posts = $q->from('site.'.$this->type.'.taxonomies.'.$this->tax)->get();
    
    $data = get_tease_term_data($posts, $this->tax);
    
    return $data;
  }
  public function get_all_posts_count() {
    
    $q = new Jsonq('../public/json/data.min.json');
    $posts = $q->from('site.'.$this->type.'.taxonomies.'.$this->tax)->get();
    $data = $posts->count();
    
    return $data;
  }
  public function get_pagination() {
    
    if($this->get_type_settings($this->type) == true) {
      $data = set_pagination_data(
        $this->get_type_settings($this->type)['posts_per_page'],
        $this->page, 
        $this->get_all_posts_count(),
        get_type_route($this->type).get_tax_route($this->tax)
      );
    } else {
      $data = null;
    }
    return $data;
  }
  public function get_paged_posts($terms) {
    if (!$this->page) {
      $page = 0;
    } else {
      $page = $this->page - 1;
    }
      
    if (!($this->get_type_settings($this->type)['posts_per_page'] * $page >= $this->get_all_posts_count())) {
      $data = get_tease_term_data($terms[$page], $this->tax);
    } else {
      $data = false;
    }
    
    return $data;
  }
  
}