<?php
// https://github.com/nahid/jsonq
use Nahid\JsonQ\Jsonq;

class Archive_model {
  
  public $page;
  public $type;
  
  public function __construct($page, $type) {
    $this->page = $page;
    $this->type = $type;
    if ($this->type == "blog") {
      $single_name = "posts";
    } elseif ($this->type == "portfolio") {
      $single_name = "projects";
    }
    $this->data_name = 'site.'.$this->type.'.meta';
    $this->items_name = 'site.'.$this->type.'.'.$single_name;
    $this->routes_name = 'site.'.$this->type.'.routes';
  }
  
  public function get_archive_routes() {
    $q = new Jsonq('../public/json/data.json');
    $data = $q->from($this->routes_name)->get();
    reset($q);
    
    return $data;
  }
  
  public function get_archive_data() {
    if (!isset($data)) $data = new stdClass();  
    $q = new Jsonq('../public/json/data.json');
    $data->meta = $q->from($this->data_name)->get();
    $data->pagination = $data->meta['pagination'];
    $data->routes = $this->get_archive_routes();
    reset($q);
    
    return $data;
  }
  
  public function get_posts() {
    $paged = $this->get_archive_data()->pagination;
    if (!isset($data)) $data = new stdClass();
    $q = new Jsonq('../public/json/data.json');
    $blog_posts = $q->find($this->items_name)->get();
    reset($q);
    $data->number_of_posts = $blog_posts->count();
    $data->posts = $this->get_paginated_posts(
      $this->page, 
      $blog_posts, 
      $data->number_of_posts, 
      $paged['posts_per_page'], 
      $paged['is_paged']
    );
    return $data;
  }
  
  public function get_pagination() {
    $paged = $this->get_archive_data()->pagination;
    $data = set_pagination_data(
      $paged, 
      $this->page, 
      $this->get_posts()->number_of_posts
    );
    return $data;
  }
  
  public function get_paginated_posts($page, $blog_posts, $number_of_posts, $posts_per_page, $is_paged) {
    $posts_pages = array_chunk(json_decode($blog_posts), $posts_per_page);
    $routes = $this->get_archive_routes();

    if ($is_paged) {
      if (!$page) {
        $page = 0;
      } else {
        $page = $page - 1;
      }
      if (!($posts_per_page * $page > $number_of_posts)) {
        $data = generate_tease_post_links($posts_pages[$page], $routes['single_url']);
      } else {
        $data = false;
      }
    } else {
      $data = generate_tease_post_links($blog_posts, $routes['single_url']);
    }
    
    return $data;
  }
  
}