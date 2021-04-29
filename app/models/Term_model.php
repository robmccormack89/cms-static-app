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
  
    $data = $this->get_archive_meta();
    $data['posts'] = $this->get_collection_terms();
    // $data['pagination'] = $this->get_archive_pagination();

    return $data;
  } 
  public function get_collection_terms() {
    if($this->tax == 'categories' || $this->tax == 'tags') {
      $pre = 'site.blog.taxonomies.';
      $q = new Jsonq('../public/json/data.min.json');
      $data = $q->from($pre.$this->tax)->get();
    } 
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
    
    $data = $this->get_archive_meta();
    $data['posts'] = $this->get_archive_posts();
    $data['pagination'] = $this->get_archive_pagination();
    
    return $data;
  } 
  public function get_tag() {
    
    $data = $this->get_archive_meta();
    $data['posts'] = $this->get_archive_posts();
    $data['pagination'] = $this->get_archive_pagination();
    
    return $data;
  } 
  
  // content type & taxonomy conditionals - edit for new content types->taxonomies
  
  // extended from Archive_model
  public function archive_locations() {
  
    if($this->type == 'blog') {
      $data = 'site.blog.posts';
    };
  
    return $data;
  }
  public function archive_meta_locations() {
    if($this->type == 'blog' && $this->term == null) {
      $q = new Jsonq('../public/json/data.min.json');
      $data = $q->from('site.blog.meta')->get();
    }
    if($this->term) {
      if($this->tax == 'categories' || $this->tax == 'tags') {
        $pre = 'site.blog.taxonomies.';
        $q = new Jsonq('../public/json/data.min.json');
        $data = $q->from($pre.$this->tax)
        ->where('slug', '=', $this->term)->first();
      } 
    }
    return $data;
  }
  public function tax_archive_url() {
  
    if($this->tax == 'categories') {
      $data = $GLOBALS['configs']['category_url'];
    } elseif($this->tax == 'tags') {
      $data = $GLOBALS['configs']['tag_url'];
    }
  
    return $data;
  }
  
  // getting meta - general functions, extended from Archive_model
  
  public function get_archive_meta() {
    
    $data = $this->archive_meta_locations();
    
    if(!$this->page) {
      $data['title'] = $data['title'];
    } else {
      $data['title'] = $data['title'].' - Page '.$this->page;
    }
    
    return $data;
  }
  public function get_archive_routes() {
    $data = $this->archive_settings();
    
    return $data['routes'];
  }
  public function get_archive_meta_pagination() {
    $data_archive_settings = $this->archive_settings();
    $data = $data_archive_settings['meta'];
    
    return $data['pagination'];
  }
  public function get_archive_pagination() {
    $pag_url = $this->tax_archive_url().'/'.$this->term;
    
    if($this->get_archive_meta_pagination()['is_paged'] == true) {
      $data = set_pagination_data(
        $this->get_archive_meta_pagination(), 
        $this->page, 
        $this->get_all_posts_and_count()->count,
        $pag_url
      );
    } else {
      $data = null;
    }
    return $data;
  }
  
  // getting posts - general functions, extended from Archive_model
  
  public function get_archive_posts() {
    
    $q = new Jsonq('../public/json/data.min.json');
    $posts = $q->from($this->archive_locations())
    ->where($this->tax, 'any', $this->term)
    ->chunk($this->get_archive_meta_pagination()['posts_per_page']);

    if ($this->get_archive_meta_pagination()['is_paged']) {
      
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
  public function get_all_posts_and_count() {
    
    if (!isset($data)) $data = new stdClass();
    
    $q = new Jsonq('../public/json/data.min.json');
    $data->posts = $q->from($this->archive_locations())
    ->where($this->tax, 'any', $this->term)
    ->get();
    $data->count = $data->posts->count();
    
    return $data;
  }
  
}