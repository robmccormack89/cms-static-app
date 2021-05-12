<?php

use Nahid\JsonQ\Jsonq;

class Archive_model extends Site_model {
  
  public $type;
  public $page;
  
  public function __construct($type, $page) {
    $this->type = $type;
    $this->page = $page;
  }

  public function get_archive() {
    
    $blog = $this->get_meta();
    $blog['posts'] = $this->get_posts();
    $blog['pagination'] = $this->get_pagination();
    
    return $blog;
  }
  
  public function get_meta() {
    $data = $this->get_type_meta($this->type);
    
    if($this->page) {
      $data['title'] = $data['title'].' (Page '.$this->page.')';
    }
    
    return $data;
  }
  public function get_posts() {
    
    $q = new Jsonq('../public/json/data.min.json');
    $posts = $q->from(get_archive_locations($this->type))
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
    $posts = $q->find(get_archive_locations($this->type))->get();
    
    $data = get_tease_data($posts, $this->type);
    
    return $data;
  }
  public function get_all_posts_count() {
    $q = new Jsonq('../public/json/data.min.json');
    $posts = $q->find(get_archive_locations($this->type))->get();
    
    $data = $posts->count();
    
    return $data;
  }
  public function get_pagination() {
    if($this->get_type_settings($this->type)['is_paged'] == true) {
  
      $data = set_pagination_data(
        $this->get_type_settings($this->type)['posts_per_page'],
        $this->page, 
        $this->get_all_posts_count(),
        get_type_route($this->type)
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