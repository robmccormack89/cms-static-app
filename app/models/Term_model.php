<?php
// https://github.com/nahid/jsonq
use Nahid\JsonQ\Jsonq;

class Term_model extends Archive_model {
  
  public $term;
  
  public function __construct($type, $page, $term) {
    parent::__construct($type, $page);
    
    $this->term = $term;
  }
  
  public function get_category() {
    
    $data = $this->get_term_meta();
    $data['posts'] = $this->get_term_archive_posts();
    $data['pagination'] = $this->get_term_pagination();
    
    return $data;
  } 

  public function term_archive_locations() {
  
    if($this->type == 'categories' || $this->type == 'tags') {
      $data = 'site.blog.posts';
    };
  
    return $data;
  }
  public function term_archive_settings() {
  
    if($this->type == 'categories' || $this->type == 'tags') {
      $data['meta'] = $this->archive_settings->get_blog_meta();
      $data['routes'] = $this->archive_settings->get_blog_settings();
    }
  
    return $data;
  }
  public function get_term_archive_routes() {
    $data = $this->term_archive_settings();
    
    return $data['routes'];
  }
  
  public function get_term_meta() {
    
    $q = new Jsonq('../public/json/data.json');
    $data = $q->from('site.blog.taxonomies.categories')
    ->where('name', '=', $this->term)
    ->first();
    
    return $data;
  }
  public function get_term_archive_meta_pagi() {
    $data_archive_settings = $this->term_archive_settings();
    $data = $data_archive_settings['meta'];
    
    return $data['pagination'];
  }
  
  public function get_term_archive_posts() {
    
    $q = new Jsonq('../public/json/data.json');
    $posts = $q->from($this->term_archive_locations())
    ->where('categories', 'any', $this->term)
    ->chunk($this->get_term_archive_meta_pagi()['posts_per_page']);

    if ($this->get_term_archive_meta_pagi()['is_paged']) {
      
      if($this->get_term_paged_posts($posts)) {
        $data = $this->get_term_paged_posts($posts);
      } else {
        $data = false;
      }
      
    } else {
      $data = $this->get_term_all_posts();
    }
    
    return $data;
  }
  
  public function get_term_paged_posts($posts) {

    if (!$this->page) {
      $page = 0;
    } else {
      $page = $this->page - 1;
    }

    if (!($this->get_term_archive_meta_pagi()['posts_per_page'] * $page >= $this->get_term_all_posts()->count)) {
      $data = generate_tease_post_links($posts[$page], $this->get_term_archive_routes()['single_url']);
    } else {
      $data = false;
    }
    
    return $data;
  }
  public function get_term_all_posts() {
    
    if (!isset($data)) $data = new stdClass();
    
    $q = new Jsonq('../public/json/data.json');
    $data->posts = $q->from($this->term_archive_locations())
    ->where('categories', 'any', $this->term)
    ->get();
    $data->count = $data->posts->count();
    
    return $data;
  }
  
  public function get_term_pagination() {
    
    $archive_url = $this->get_term_archive_routes()['archive_url'];
    $pag_url = $archive_url.'/'.$this->term;
    
    if($this->get_term_archive_meta_pagi()['is_paged'] == true) {
      $data = set_pagination_data(
        $this->get_term_archive_meta_pagi(), 
        $this->page, 
        $this->get_term_all_posts()->count,
        $pag_url
      );
    } else {
      $data = null;
    }
    return $data;
  }
  
}