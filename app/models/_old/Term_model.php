<?php

use Nahid\JsonQ\Jsonq;

class Term_model extends Archive_model {
  
  public $tax;
  public $term;
  
  public function __construct($type, $page, $tax, $term) {
    parent::__construct($type, $page);
    $this->tax = $tax;
    $this->term = $term;
  }
  
  public function get_term() {
    
    $taxonomy = $this->get_meta();
    $taxonomy['posts'] = $this->get_posts();
    $taxonomy['pagination'] = $this->get_pagination();
    
    return $taxonomy;
  }
  
  public function get_meta() {
    $data = $this->get_tax_term($this->type, $this->tax, $this->term);
    
    if($this->page) {
      $data['title'] = $data['title'].' (Page '.$this->page.')';
    }
    
    return $data;
  }
  public function get_posts() {
    
    $q = new Jsonq('../public/json/data.min.json');
    $posts = $q->from(get_archive_locations($this->type))
    ->where($this->tax, 'any', $this->term)
    ->chunk($this->get_type_settings($this->type)['posts_per_page']);

    if ($this->get_type_settings($this->type)['is_paged']) {
      
      if($this->get_paged_posts($posts)) {
        $data = $this->get_paged_posts($posts);
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
    $posts = $q->from(get_archive_locations($this->type))
    ->where($this->tax, 'any', $this->term)
    ->get();
    
    $data = get_tease_data($posts, $this->type);
    
    return $data;
  }
  public function get_all_posts_count() {
    
    $q = new Jsonq('../public/json/data.min.json');
    $posts = $q->from(get_archive_locations($this->type))
    ->where($this->tax, 'any', $this->term)
    ->get();
    $data = $posts->count();
    
    return $data;
  }
  public function get_pagination() {
    if($this->get_type_settings($this->type)['is_paged'] == true) {
  
      $data = set_pagination_data(
        $this->get_type_settings($this->type)['posts_per_page'],
        $this->page, 
        $this->get_all_posts_count(),
        get_type_route($this->type).get_tax_route($this->tax).'/'.$this->term
      );
  
    } else {
      $data = null;
    }
    return $data;
  }
  public function get_paged_posts($posts) {

    if (!$this->page) {
      $page = 0;
    } else {
      $page = $this->page - 1;
    }

    if (!($this->get_type_settings($this->type)['posts_per_page'] * $page >= $this->get_all_posts_count())) {
      $data = get_tease_data($posts[$page], $this->type);
    } else {
      $data = false;
    }
    
    return $data;
    
  }
  
}