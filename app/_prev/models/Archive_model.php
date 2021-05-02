<?php
// https://github.com/nahid/jsonq
use Nahid\JsonQ\Jsonq;

class Archive_model extends Site_model {
  
  // main archive type; eg: blog or portfolio
  public $type;
  // paged value, for paginated archives
  public $page;
  
  public function __construct($type, $page) {
    $this->type = $type;
    $this->page = $page;
    
    // $this->archive_settings = new Site_model;
  }

  public function get_blog() {
    
    $blog = $this->get_archive_meta();
    $blog['posts'] = $this->get_archive_posts();
    $blog['pagination'] = $this->get_archive_pagination();
    
    return $blog;
  }  
  
  public function get_portfolio() {
    
    $portfolio = $this->get_archive_meta();
    $portfolio['posts'] = $this->get_archive_posts();
    $portfolio['pagination'] = $this->get_archive_pagination();
    
    return $portfolio;
  }
  
  //

  public function archive_locations() {
  
    if($this->type == 'blog') {
      $data = 'site.blog.posts';
    } elseif($this->type == 'portfolio') {
      $data = 'site.portfolio.projects';
    }
  
    return $data;
  }
  
  public function archive_settings() {
  
    if($this->type == 'blog') {
      $data['meta'] = $this->get_type_meta($this->type);
      $data['routes'] = $this->get_type_settings($this->type);
    } elseif($this->type == 'portfolio') {
      $data['meta'] = $this->get_type_meta($this->type);
      $data['routes'] = $this->get_type_settings($this->type);
    }
  
    return $data;
  }
  public function get_archive_meta() {
    $data = $this->archive_settings();
    
    if(!$this->page) {
      $data['meta']['title'] = $data['meta']['title'];
    } else {
      $data['meta']['title'] = $data['meta']['title'].' - Page '.$this->page;
    }
    
    return $data['meta'];
  }
  public function get_archive_routes() {
    $data = $this->archive_settings();
    
    return $data['routes'];
  }

  public function get_archive_meta_pagination() {
    $data = $this->get_archive_meta();
    
    return $data['pagination'];
  }

  public function get_archive_pagination() {
    if($this->get_archive_meta_pagination()['is_paged'] == true) {
      $data = set_pagination_data(
        $this->get_archive_meta_pagination(), 
        $this->page, 
        $this->get_all_posts_and_count()->count,
        $this->get_archive_routes()['archive_url']
      );
    } else {
      $data = null;
    }
    return $data;
  }
  
  // getting posts - general functions
  

  public function get_all_posts_and_count() {
    
    if (!isset($data)) $data = new stdClass();
    $q = new Jsonq('../public/json/data.min.json');
    $data->posts = $q->find($this->archive_locations())->get();
    $data->count = $data->posts->count();
    
    return $data;
  }

  public function get_archive_posts() {
    
    $q = new Jsonq('../public/json/data.min.json');
    $posts = $q->from($this->archive_locations())->chunk($this->get_archive_meta_pagination()['posts_per_page']);

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

  public function get_paged_posts($posts) {

    if (!$this->page) {
      $page = 0;
    } else {
      $page = $this->page - 1;
    }

    if (!($this->get_archive_meta_pagination()['posts_per_page'] * $page >= $this->get_all_posts_and_count()->count)) {
      $data = generate_tease_post_links($posts[$page], $this->get_archive_routes()['single_url']);
    } else {
      $data = false;
    }
    
    return $data;
    
  }

  public function get_all_posts() {
    // turn json object to php array
    $posts = json_decode( $this->get_all_posts_and_count()->posts, TRUE );
    $data = generate_tease_post_links($posts, $this->get_archive_routes()['single_url']);
    return $data;
  }
  
}