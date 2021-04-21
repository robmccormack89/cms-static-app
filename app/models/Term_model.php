<?php
// https://github.com/nahid/jsonq
use Nahid\JsonQ\Jsonq;

class Term_model extends Archive_model {
  
  public $term;
  
  public function __construct($type, $page, $term) {
    parent::__construct($type, $page);
    
    $this->term = $term;
  }
  
  // for the category taxonomy archives (cpts)
  public function get_category() {
    
    $data = $this->get_term_meta();
    $data['posts'] = $this->get_term_archive_posts();
    $data['pagination'] = $this->get_term_pagination();
    
    return $data;
  } 
  // (cpts)
  public function get_tag() {
    
    $data = $this->get_term_meta();
    $data['posts'] = $this->get_term_archive_posts();
    $data['pagination'] = $this->get_term_pagination();
    
    return $data;
  } 
  // (cpts)
  // this sets the given taxonomy to a content-type's posts (categories & tags are part of blog)
  // add new taxonomies to other content types here
  public function term_archive_locations() {
  
    if($this->type == 'categories' || $this->type == 'tags') {
      $data = 'site.blog.posts';
    };
  
    return $data;
  }
  // (cpts)
  // this sets the given taxonomy to a content-type's taxonomies (categories & tags are part of blog taxonomies)
  // add new taxonomies to other content types here
  public function term_locations() {
  
    if($this->type == 'categories' || $this->type == 'tags') {
      $data = 'site.blog.taxonomies.';
    };
  
    return $data;
  }
  // (cpts)
  // this sets a given taxonomy to its content type's settings
  // add new taxonomies to other content types here
  public function term_archive_settings() {
  
    if($this->type == 'categories' || $this->type == 'tags') {
      $data['meta'] = $this->archive_settings->get_blog_meta();
      $data['routes'] = $this->archive_settings->get_blog_settings();
    }
  
    return $data;
  }
  // (cpts)
  // this sets an acrhive name for a given taxonomy (to be used in pagination urls)
  // add new taxonomies here
  public function taxonomy_archive_url() {
  
    if($this->type == 'categories') {
      $data = $GLOBALS['configs']['category_url'];
    } elseif($this->type == 'tags') {
      $data = $GLOBALS['configs']['tag_url'];
    }
  
    return $data;
  }
  
  // general term archive functions
  public function get_term_archive_routes() {
    $data = $this->term_archive_settings();
    
    return $data['routes'];
  }
  public function get_term_meta() {
    
    $q = new Jsonq('../public/json/data.min.json');
    $location = $this->term_locations().$this->type;
    $data = $q->from($location)
    ->where('name', '=', $this->term)
    ->first();
    
    if(!$this->page) {
      $data['title'] = $data['title'];
    } else {
      $data['title'] = $data['title'].' - Page '.$this->page;
    }
    
    return $data;
  }
  public function get_term_archive_meta_pagi() {
    $data_archive_settings = $this->term_archive_settings();
    $data = $data_archive_settings['meta'];
    
    return $data['pagination'];
  }
  public function get_term_archive_posts() {
    
    $q = new Jsonq('../public/json/data.min.json');
    $posts = $q->from($this->term_archive_locations())
    ->where($this->type, 'any', $this->term)
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
    
    $q = new Jsonq('../public/json/data.min.json');
    $data->posts = $q->from($this->term_archive_locations())
    ->where($this->type, 'any', $this->term)
    ->get();
    $data->count = $data->posts->count();
    
    return $data;
  }
  public function get_term_pagination() {
    $pag_url = $this->taxonomy_archive_url().'/'.$this->term;
    
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