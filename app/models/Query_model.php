<?php
// https://github.com/nahid/jsonq
use Nahid\JsonQ\Jsonq;

class Query_model {
  
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
    $this->category = $this->get_category_params();
    $this->tag = $this->get_tag_params();
    $this->date = $this->get_date_params();
    // settings
    $this->posts_per_page = $this->get_posts_per_page_params();
    $this->paged = $this->get_paged_params();
    $this->layout = $this->get_layout_params();
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
      $q = new Jsonq('../public/json/data.min.json');
      $res = $q
      ->from(get_archive_locations($this->type))
      ->get();
    }
    
    if($this->category) {
      $json = new Nahid\JsonQ\Jsonq($res);
      $res = $json->where('categories', 'any', $this->category)->get();
    }
    
    if($this->tag) {
      $json2 = new Nahid\JsonQ\Jsonq($res);
      $res = $json2->where('tags', 'any', $this->tag)->get();
    }
    
    if($this->date) {
      $json3 = new Nahid\JsonQ\Jsonq($res);
      $res = $json3->whereContains('date_time', $this->date)->get();
    }
    
    return $res;

  }
  public function get_all_posts() {
    $q = new Jsonq('../public/json/data.min.json');
    $res = $q->find('site.blog.posts');
    $q2 = $q->copy();
    $res2 = $q2->reset()->find('site.portfolio.projects');
    
    $array1 = json_decode($res, true);
    $array2 = json_decode($res2, true);
    $result = array_merge($array1, $array2);
    
    $json = new Nahid\JsonQ\Jsonq();
    $yee = $json->collect($result);

    return $yee;
  }
  public function get_all_posts_count() {

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