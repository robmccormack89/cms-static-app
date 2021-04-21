<?php
// https://github.com/nahid/jsonq
use Nahid\JsonQ\Jsonq;

class Term_model extends Archive_model {
  
  // taxonomy type
  public $tax;
  // taxonomy term
  public $term;
  
  public function __construct($type, $page, $tax, $term) {
    // inherit type & page from Archive_model
    parent::__construct($type, $page);
    $this->tax = $tax;
    $this->term = $term;
  }
  
  // in progress
  
  public function get_cat_collection() {
  
    $data = $this->get_term_meta();
    // $data['posts'] = $this->get_term_archive_posts();
    // $data['pagination'] = $this->get_term_pagination();
  
    // $data = '';
  
    return $data;
  } 
  
  // main functions for term archives - specfic to new taxonomoes
  
  /**
   * getting all the data for the catgeory term archive
   * builds the catgeory term archive data in three parts: meta, posts & pagination
   * data returned is accessible at data, data.posts & data.pagination
   * used fns can be used for taxonomy term archive/s data: like category & tags. see get_tag
   *
   * @return object|array
   */
  public function get_category() {
    
    $data = $this->get_term_meta();
    $data['posts'] = $this->get_term_archive_posts();
    $data['pagination'] = $this->get_term_pagination();
    
    return $data;
  } 
  public function get_tag() {
    
    $data = $this->get_term_meta();
    $data['posts'] = $this->get_term_archive_posts();
    $data['pagination'] = $this->get_term_pagination();
    
    return $data;
  } 
  
  // content type & taxonomy conditionals - edit for new content types->taxonomies
  
  public function set_term_locations() {
    
    if($this->type == 'blog' && $this->term == null) {
      $data = 'site.';
    }
  
    if($this->tax == 'categories' || $this->tax == 'tags') {
      $data = 'site.blog.taxonomies.';
    } 
  
    return $data;
  }
  public function set_term_posts_locations() {
  
    if($this->type == 'blog') {
      $data = 'site.blog.posts';
    };
  
    return $data;
  }
  public function set_term_meta_locations() {
    if($this->type == 'blog' && $this->term == null) {
      $q = new Jsonq('../public/json/data.min.json');
      $data = $q->from('site.blog.meta')->get();
    }
    if($this->tax == 'categories' || $this->tax == 'tags') {
      $pre = 'site.blog.taxonomies.';
      $q = new Jsonq('../public/json/data.min.json');
      $data = $q->from($pre.$this->tax)
      ->where('name', '=', $this->term)->first();
    } 
    return $data;
  }
  public function set_term_settings_locations() {
  
    if($this->type == 'blog') {
      $data['meta'] = $this->archive_settings->get_blog_meta();
      $data['routes'] = $this->archive_settings->get_blog_settings();
    } 
  
    return $data;
  }
  public function set_tax_archive_url() {
  
    if($this->tax == 'categories') {
      $data = $GLOBALS['configs']['category_url'];
    } elseif($this->tax == 'tags') {
      $data = $GLOBALS['configs']['tag_url'];
    }
  
    return $data;
  }
  
  // getting meta - general functions
  
  public function get_term_meta() {
    
    $data = $this->set_term_meta_locations();
    
    if(!$this->page) {
      $data['title'] = $data['title'];
    } else {
      $data['title'] = $data['title'].' - Page '.$this->page;
    }
    
    return $data;
  }
  public function get_term_archive_routes() {
    $data = $this->set_term_settings_locations();
    
    return $data['routes'];
  }
  public function get_term_archive_meta_pagi() {
    $data_archive_settings = $this->set_term_settings_locations();
    $data = $data_archive_settings['meta'];
    
    return $data['pagination'];
  }
  public function get_term_pagination() {
    $pag_url = $this->set_tax_archive_url().'/'.$this->term;
    
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
  
  // getting posts - general functions
  
  public function get_term_archive_posts() {
    
    $q = new Jsonq('../public/json/data.min.json');
    $posts = $q->from($this->set_term_posts_locations())
    ->where($this->tax, 'any', $this->term)
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
    $data->posts = $q->from($this->set_term_posts_locations())
    ->where($this->tax, 'any', $this->term)
    ->get();
    $data->count = $data->posts->count();
    
    return $data;
  }
  
}