<?php
namespace Rmcc;

class QueryModel {

  // e.g http://static.com/?s=lorem&type=post&cat=events&tag=javascript&date=202
  
  // &show_all
  
  // &per_page = 4
  
  // &p = 2
  
  public function __construct($string) {
    $this->string = $string;

    // params taken from string
    $this->paged = $this->get_paged_params();
    $this->page = $this->get_page_params();
    $this->posts_per_page = $this->get_per_page_params();
    $this->search = $this->get_search_params();
    $this->tag = $this->get_tag_params();
    $this->date = $this->get_date_params();
    
    // taxonomy specific
    $this->type = $this->get_type_params();
    $this->category = $this->get_category_params();
    
    // result
    $this->query = $this->getQuery();
  }
  
  // new, replaces fetch()
  public function getQuery() {
    $archive['title'] = 'Search Results';
    $archive['description'] = 'This is the Search results page. Query = '.$this->string;
    
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
    
    // $pag_obj = new PaginationModel(
    //   $this->posts_per_page, $this->page, $posts_obj->all_count, $this->paged, $GLOBALS['config']['current_url']
    // );
    // $archive['pagination'] = $pag_obj->pagination;
    
    return $archive;
  }
  
  public function get_paged_params() {
    parse_str($this->string, $data);
    if (isset($data['show_all'])) :
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
  
  // public function get_posts_per_page_params() {
  //   parse_str($this->string, $data);
  //   if (isset($data['posts_per_page'])) :
  //   return $data['posts_per_page'];
  //   endif;
  // }
  // public function get_paged_params() {
  //   parse_str($this->string, $data);
  //   if (isset($data['paged'])) :
  //   return $data['paged'];
  //   endif;
  // }
  // public function get_layout_params() {
  //   parse_str($this->string, $data);
  //   if (isset($data['layout'])) :
  //   return $data['layout'];
  //   endif;
  // }
  
}