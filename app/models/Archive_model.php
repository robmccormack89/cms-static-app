<?php
// https://github.com/nahid/jsonq
use Nahid\JsonQ\Jsonq;

class Archive_model {
  
  // main archive type; eg: blog or portfolio
  public $type;
  // paged value, for paginated archives
  public $page;
  
  public function __construct($type, $page) {
    $this->type = $type;
    $this->page = $page;
    
    $this->archive_settings = new Settings_model;
  }
  
  // main functions for archives - specific to content types
  
  /**
   * getting all the data for the blog (cpts)
   * builds the main blog archive data in three parts: meta, posts & pagination
   * data returned is accessible at data, data.posts & data.pagination
   * used fns can be used for get main archive/s data: content types like blog, portfolio. see get_portfolio
   *
   * @return object|array
   */
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
  
  // content types conditionals - edit for new content types
  
  /**
   * sets the archive posts json data location depending on $this->type (cpts)
   *
   * @return object|array
   */
  public function archive_locations() {
  
    if($this->type == 'blog') {
      $data = 'site.blog.posts';
    } elseif($this->type == 'portfolio') {
      $data = 'site.portfolio.projects';
    }
  
    return $data;
  }
  /**
   * getting routes & meta settings for main archive type from settings model depending on $this->type (cpts)
   *
   * @return object|array
   */
  public function archive_settings() {
  
    if($this->type == 'blog') {
      $data['meta'] = $this->archive_settings->get_blog_meta();
      $data['routes'] = $this->archive_settings->get_blog_settings();
    } elseif($this->type == 'portfolio') {
      $data['meta'] = $this->archive_settings->get_portfolio_meta();
      $data['routes'] = $this->archive_settings->get_portfolio_settings();
    }
  
    return $data;
  }
  
  // getting meta - general functions
  
  /**
   * getting archive meta data from archive_settings
   *
   * @return object|array returns archive meta data such as title, name, description
   */
  public function get_archive_meta() {
    $data = $this->archive_settings();
    
    if(!$this->page) {
      $data['meta']['title'] = $data['meta']['title'];
    } else {
      $data['meta']['title'] = $data['meta']['title'].' - Page '.$this->page;
    }
    
    return $data['meta'];
  }
  /**
   * getting archive routes data from archive_settings
   *
   * @return object|array returns archive routes data: archive singular & archive urls 
   */
  public function get_archive_routes() {
    $data = $this->archive_settings();
    
    return $data['routes'];
  }
  /**
   * getting archive meta pagination data get_archive_meta
   *
   * @return object|array returns archive meta pagination data: is_paged & posts_per_page settings
   */
  public function get_archive_meta_pagi() {
    $data = $this->get_archive_meta();
    
    return $data['pagination'];
  }
  /**
   * setting the paginated archive pagination data
   *
   * @return object|array modifies archive meta pagination data with pagination links to be used under archive.pagination
   */
  public function get_archive_pagination() {
    if($this->get_archive_meta_pagi()['is_paged'] == true) {
      $data = set_pagination_data(
        $this->get_archive_meta_pagi(), 
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
  
  /**
   * getting all posts & their count from archive locations
   *
   * @return object|array,integer $data->posts(object|array), $data->count(integer)
   */
  public function get_all_posts_and_count() {
    
    if (!isset($data)) $data = new stdClass();
    $q = new Jsonq('../public/json/data.min.json');
    $data->posts = $q->find($this->archive_locations())->get();
    $data->count = $data->posts->count();
    
    return $data;
  }
  /**
   * getting main archive posts data
   *
   * @return object|array returns archive posts data
   */
  public function get_archive_posts() {
    
    $q = new Jsonq('../public/json/data.min.json');
    $posts = $q->from($this->archive_locations())->chunk($this->get_archive_meta_pagi()['posts_per_page']);

    if ($this->get_archive_meta_pagi()['is_paged']) {
      
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
  /**
   * getting paged posts depending on $this->page & $this->type
   *
   * @return object|array returns paginated posts data
   */
  public function get_paged_posts($posts) {

    if (!$this->page) {
      $page = 0;
    } else {
      $page = $this->page - 1;
    }

    if (!($this->get_archive_meta_pagi()['posts_per_page'] * $page >= $this->get_all_posts_and_count()->count)) {
      $data = generate_tease_post_links($posts[$page], $this->get_archive_routes()['single_url']);
    } else {
      $data = false;
    }
    
    return $data;
    
  }
  /**
   * getting all posts from get_all_posts_and_count depending on $this->page & $this->type
   *
   * @return object|array returns all posts data
   */
  public function get_all_posts() {
    // turn json object to php array
    $posts = json_decode( $this->get_all_posts_and_count()->posts, TRUE );
    $data = generate_tease_post_links($posts, $this->get_archive_routes()['single_url']);
    return $data;
  }
  
}