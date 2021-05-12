<?php
// https://github.com/nahid/jsonq
use Nahid\JsonQ\Jsonq;

class Query {
  
  // ?search=lorem&type=post&category=news&tag=twig&date=2021&posts_per_page=4&paged=2&layout=list
  
  public $string;
  public $search;
  public $type;
  public $category;
  public $tag;
  public $date;
  public $posts_per_page;
  public $paged;
  public $layout;
  
  public function __construct($string) {
    // query string part of Query construction
    $this->string = $string;
    // query paramaters
    $this->search = $this->get_search_params();
    $this->type = $this->get_type_params();
    // category & tag are related only to type: post. will show empty when type is project
    $this->category = $this->get_category_params();
    $this->tag = $this->get_tag_params();
    $this->date = $this->get_date_params();
    // settings
    $this->posts_per_page = $this->get_posts_per_page_params();
    $this->paged = $this->get_paged_params();
    $this->layout = $this->get_layout_params();
  }
  
  public function switch_type() {
    if ($this->type == 'post') {
      return 'blog';
    } elseif ($this->type == 'project') {
      return 'portfolio';
    }
  }
  
  public function fetch() {
    $query = $this->get_meta();
    $query['posts'] = $this->get_posts();
    // $query['pagination'] = $this->get_pagination();
    
    return $query;
  }
  
  public function get_meta() {
    $meta['title'] = 'Search Results';
    $meta['description'] = 'This is the Search results page. Query = '.$this->string;
        
    return $meta;
  }
  public function get_posts() {

    if($this->type){
      // Jsonq = regular jsonq class which extends qarray, see vendor
      $q1 = new Jsonq('../public/json/data.min.json');
      $posts = $q1
      ->from(get_archive_locations($this->type))
      ->get();
    } else {
      $data = $this->get_all_posts();
    }
    
    if($this->search) {
      // Json_model extends Jsonq, used for custom ConditionalFactory from qarray
      // reason for this is to use custom match conditional
      // so can use proper case insensitive regex
      $q2 = new Json_model($data);
      $posts = $q2
      ->where('excerpt', 'match', '(?i)ipsum')
      ->orWhere('title', 'match', '(?i)ipsum')
      ->get();
    }
    
    if($this->category) {
      $q3 = new Nahid\JsonQ\Jsonq($data);
      $posts = $q3->where('categories', 'any', $this->category)->get();
    }
    
    if($this->tag) {
      $q4 = new Nahid\JsonQ\Jsonq($data);
      $posts = $q4->where('tags', 'any', $this->tag)->get();
    }
    
    if($this->date) {
      $q5 = new Nahid\JsonQ\Jsonq($data);
      $posts = $q5->whereContains('date_time', $this->date)->get();
    }
    
    $data = get_tease_data($posts, $this->type);
    
    return $data;

  }
  
  // get all posts (post & project type, with no other filters)
  public function get_all_posts() {
    $q1 = new Jsonq('../public/json/data.min.json');
    $res1 = $q1->find('site.blog.posts');
    $q2 = $q1->copy();
    $res2 = $q2->reset()->find('site.portfolio.projects');
    
    $array1 = json_decode($res1, true);
    $array2 = json_decode($res2, true);
    $merge = array_merge($array1, $array2);
    
    $json = new Nahid\JsonQ\Jsonq();
    $data = $json->collect($merge);

    return $data;
  }
  // get_all_posts count
  public function get_all_posts_count() {
    $data = $this->get_all_posts();
    
    $count = $data->count();
    
    return $count;
  }
  public function get_pagination() {

  }
  public function get_paged_posts($posts) {


    
  }
  
  public function get_search_params() {
    parse_str($this->string, $data);
    if (isset($data['search'])) :
    return $data['search'];
    endif;
  }
  public function get_type_params() {
    parse_str($this->string, $data);
    if (isset($data['type'])) :
    return $data['type'];
    endif;
  }
  public function get_category_params() {
    parse_str($this->string, $data);
    if (isset($data['category'])) :
    return $data['category'];
    endif;
  }
  public function get_tag_params() {
    parse_str($this->string, $data);
    if (isset($data['tag'])) :
    return $data['tag'];
    endif;
  }
  public function get_date_params() {
    parse_str($this->string, $data);
    if (isset($data['date'])) :
    return $data['date'];
    endif;
  }
  public function get_posts_per_page_params() {
    parse_str($this->string, $data);
    if (isset($data['posts_per_page'])) :
    return $data['posts_per_page'];
    endif;
  }
  public function get_paged_params() {
    parse_str($this->string, $data);
    if (isset($data['paged'])) :
    return $data['paged'];
    endif;
  }
  public function get_layout_params() {
    parse_str($this->string, $data);
    if (isset($data['layout'])) :
    return $data['layout'];
    endif;
  }
  
}