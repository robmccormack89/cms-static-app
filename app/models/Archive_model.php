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
  
  public function get_all_posts() {
    if (!isset($data)) $data = new stdClass();
    $q = new Jsonq('../public/json/data.json');
    $data->posts = $q->find($this->items_name)->get();
    $data->count = $data->posts->count();
    reset($q);
    
    return $data;
  }
  
  public function get_posts() {
    $all_posts = $this->get_all_posts()->posts;
    $number_of_posts = $this->get_all_posts()->count;
    $routes = $this->get_archive_data()->routes;
    $paged = $this->get_archive_data()->pagination;
    $q = new Jsonq('../public/json/data.json');
    $blog_posts = $q->from($this->items_name)->chunk($paged['posts_per_page']);

    if ($paged['is_paged']) {
      
      if (!$this->page) {
        $page = 0;
      } else {
        $page = $this->page - 1;
      }

      if (!($paged['posts_per_page'] * $page > $number_of_posts)) {
        $data = generate_tease_post_links($blog_posts[$page], $routes['single_url']);
      } else {
        $data = false;
      }
      
    } else {
      $data = generate_tease_post_links($all_posts, $routes['single_url']);
    }
    
    return $data;
  }
  
  public function get_pagination() {
    $paged = $this->get_archive_data()->pagination;
    if($paged['is_paged'] == true) {
      $data = set_pagination_data(
        $paged, 
        $this->page, 
        $this->get_all_posts()->count
      );
    } else {
      $data = '';
    }
    return $data;
  }
  
}