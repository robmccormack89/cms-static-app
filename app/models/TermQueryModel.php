<?php
namespace Rmcc;

class TermQueryModel {
  
  /*
  *
  * Examples of inputs into this class
  *
  */
  protected static $_argsString = 'taxonomy=category&orderby=name&order=asc&hide_empty=false';
  protected static $_argsArray = array(
    'type' => 'blog',
    'taxonomy' => 'category',
    'orderby' => 'name',
    'order' => 'ASC',
    'hide_empty' => false,
    // the pagination stuff...
    'per_page' => 3,
    'p' => 1,
    'show_all' => false
  );
      
  public function __construct(array $args) {
    $this->query = self::$_argsString; // Holds the query string that was passed to the query object
    $this->query_vars = self::$_argsArray;
    $this->init();
  }
  
  private function getAllTerms() {
    $q = new Json('../public/json/data.min.json');
    $posts = $q->from('site.content_types.'.$this->type.'.taxonomies.'.$this->tax)
    ->get();
    
    return $data;
    
    
    
    $types = array('blog', 'portfolio');
    
    
    $q1 = new Json('../public/json/data.min.json');
    $res1 = $q1->find('site.content_types.blog.posts'); // get posts
    $array1 = json_decode($res1, true);
    
    $q2 = $q1->copy();
    $res2 = $q2->reset()->find('site.content_types.portfolio.projects'); // get projects
    $array2 = json_decode($res2, true);
    
    $q3 = $q1->copy();
    $res3 = $q3->reset()->find('site.content_types.page'); // get pages
    $array3 = json_decode($res3, true);

    $merge = array_merge($array1, $array2, $array3); // merge the different content types
    
    $data = (new Json())->collect($merge); // create a new Json object with the merged content types

    return $data;
  }
  
  // KEYS
  private function typeKey() {
    if(array_key_exists('type', $this->query_vars)) return $this->query_vars['type'];
    return false;
  }
  private function taxKey() {
    if(array_key_exists('taxonomy', $this->query_vars)) return $this->query_vars['taxonomy'];
    return false;
  }
  private function orderbyKey() {
    if(array_key_exists('orderby', $this->query_vars)) return $this->query_vars['orderby'];
    return false;
  }
  private function orderKey() {
    if(array_key_exists('order', $this->query_vars)) return $this->query_vars['order'];
    return false;
  }
  private function hideEmptyKey() {
    if(array_key_exists('hide_empty', $this->query_vars)) return $this->query_vars['hide_empty'];
    return false;
  }
  private function perPageKey() {
    if(array_key_exists('per_page', $this->query_vars)) return $this->query_vars['per_page'];
    return false;
  }
  private function pagedKey() {
    if(array_key_exists('p', $this->query_vars)) return $this->query_vars['p'];
    return false;
  }
  private function showAllKey() {
    if(array_key_exists('show_all', $this->query_vars)) return $this->query_vars['show_all'];
    return false;
  }
  
  // get/set the keys. NO ARRAYS!!!

  // get all taxonomies

  // query down those taxonomies based on $args

  // return the results with method query() 
  
  // on initialize, do this stuff...
  private function init() {
  }
  
}