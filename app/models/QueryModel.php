<?php
namespace Rmcc;

class QueryModel {
  
  // how to do searches & querys using paramters
  
  // With type & related taxonomy: http://static.com/?s=lorem&date=2021&type=post&cat=news&tag=twig
  // or
  // Without type or taxonomy (uses archived post types): http://static.com/?s=lorem&date=2021

  // With pagination: http://static.com/?s=lorem&date=2021&per_page=3&p=2
  // or
  // With default pagination settings(posts_per_page = 4): http://static.com/?s=lorem&date=2021
  // or
  // Without pagination: http://static.com/?s=lorem&type=post&cat=news&tag=twig&date=2021&show_all
  
  public function __construct($string) {
    $this->string = $string;

    // params taken from string
    $this->paged = $this->get_paged_params(); // &show_all (use this instead of p & per_page to show all without pagination)
    $this->page = $this->get_page_params(); // &p=2 (paged value)
    $this->posts_per_page = $this->get_per_page_params(); // &per_page=4
    $this->search = $this->get_search_params(); // &s=lorem (looks in title & excerpt)
    $this->date = $this->get_date_params(); // &date=2021
    
    // taxonomy specific
    $this->type = $this->get_type_params(); // &type=post
    $this->category = $this->get_category_params(); // &cat=news
    $this->tag = $this->get_tag_params(); // &tag=twig
    
    // result
    $this->query = $this->getQuery();
  }
  
  // new, replaces fetch()
  public function getQuery() {
    $archive['title'] = 'Search Results';
    $archive['description'] = 'This is the Search results page. Query = '.$this->search;
    
    $posts_obj = new QueryPostsModel(
      $this->paged, 
      $this->page, 
      $this->posts_per_page, 
      $this->search, 
      $this->type, 
      $this->date, 
      $this->category, 
      $this->tag
    );
    $archive['posts'] = $posts_obj->posts;
    
    $pag_obj = new QueryPaginationModel(
      $this->posts_per_page, $this->page, $posts_obj->all_count, $this->paged, $_SERVER['REQUEST_URI']
    );
    $archive['pagination'] = $pag_obj->pagination;
    
    return $archive;
  }
  
  public function get_paged_params() {
    parse_str($this->string, $data);
    if (isset($data['show_all']) && !isset($data['p'])) :
      return false;
    else:
      return true;
    endif;
  }
  public function get_page_params() {
    parse_str($this->string, $data);
    if (isset($data['p'])) :
      return $data['p'];
    else:
      return null;
    endif;
  }
  public function get_per_page_params() {
    parse_str($this->string, $data);
    if (isset($data['per_page'])) :
      return $data['per_page'];
    else:
      return 4;
    endif;
  }
  
  public function get_search_params() {
    parse_str($this->string, $data);
    if (isset($data['s'])) :
    return $data['s'];
    endif;
  }
  public function get_type_params() {
    parse_str($this->string, $data);
    if (isset($data['type'])) :
    return $data['type'];
    endif;
  }
  public function get_date_params() {
    parse_str($this->string, $data);
    if (isset($data['date'])) :
    return $data['date'];
    endif;
  }
  
  public function get_category_params() {
    parse_str($this->string, $data);
    if (isset($data['cat'])) :
      return $data['cat'];
    endif;
  }
  public function get_tag_params() {
    parse_str($this->string, $data);
    if (isset($data['tag'])) :
      return $data['tag'];
    endif;
  }
  
}