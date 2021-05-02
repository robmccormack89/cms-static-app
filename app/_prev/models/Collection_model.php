<?php
// https://github.com/nahid/jsonq
use Nahid\JsonQ\Jsonq;

class Collection_model extends Term_model {
  
  public function __construct($type, $page, $tax) {
    parent::__construct($type, $page, $tax, null);
  }
  
  // main functions for term archives - specfic to new taxonomies
  
  public function get_category_collection() {
  
    $data = $this->get_archive_meta();
    $data['title'] = $this->collection_archive_title();
    $data['posts'] = $this->get_collection();
    $data['pagination'] = $this->get_archive_pagination();

    return $data;
  } 
  public function get_tag_collection() {
  
    $data = $this->get_archive_meta();
    $data['title'] = $this->collection_archive_title();
    $data['posts'] = $this->get_collection();
    $data['pagination'] = $this->get_archive_pagination();

    return $data;
  } 
  public function collection_archive_url() {
    if($this->tax == 'categories') {
      $data = $this->get_archive_routes()['category_url'];
    } elseif($this->tax == 'tags') {
      $data = $this->get_archive_routes()['tag_url'];
    }
    return $data;
  }  
  public function collection_archive_title() {
    if($this->tax == 'categories') {
      $title = 'Blog Categories';
    } elseif($this->tax == 'tags') {
      $title = 'Blog Tags';
    }
    if(!$this->page) {
      $data = $title;
    } else {
      $data = $title.' - Page '.$this->page;
    }
    return $data;
  }
  
  // getting the collection, general functions
  
  public function get_collection() {
    
    $pre = 'site.'.$this->type.'.taxonomies.';
    $q = new Jsonq('../public/json/data.min.json');
    $terms = $q->from($pre.$this->tax)->chunk($this->get_archive_meta_pagination()['posts_per_page']);
      
    if ($this->get_archive_meta_pagination()['is_paged']) {
        
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
  
  // getting posts - general functions, extended from Archive_model->Term_model
  
  public function get_archive_pagination() {
    $pag_url = $this->tax_archive_url();
    
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
  public function get_paged_posts($terms) {
    if (!$this->page) {
      $page = 0;
    } else {
      $page = $this->page - 1;
    }
      
    if (!($this->get_archive_meta_pagination()['posts_per_page'] * $page >= $this->get_all_posts_and_count()->count)) {
      $data = generate_tease_term_links($terms[$page], $this->collection_archive_url());
    } else {
      $data = false;
    }
    
    return $data;
  }
  public function get_all_posts() {
    // turn json object to php array
    $posts = json_decode( $this->get_all_posts_and_count()->posts, TRUE );
    $data = generate_tease_post_links($posts, $this->collection_archive_url());
    return $data;
  }
  public function get_all_posts_and_count() {
    if (!isset($data)) $data = new stdClass();
    
    $pre = 'site.'.$this->type.'.taxonomies.';
    $q = new Jsonq('../public/json/data.min.json');
    $data->posts = $q->from($pre.$this->tax)->get();
    $data->count = $data->posts->count();
    
    return $data;
  }
  
}