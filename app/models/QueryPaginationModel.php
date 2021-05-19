<?php
namespace Rmcc;

class QueryPaginationModel extends PaginationModel {
  
  public function __construct($posts_per_page, $page, $count, $paged, $url) {
    parent::__construct($posts_per_page, $page, $count, $paged, $url);
    
    $this->pagination = $this->getPagination();
  }
  
  // set the pagination data
  protected function setPaginationData() {
    
    if(!$this->page) {
      $page = 1;
    } else {
      $page = $this->page;
    }
    
    // step 1 - setup. data is blank. paged is the archive paging route. if req page is blank, set it to 1
    $data[] = '';
    
    // step 2 - if has next|prev, set the links data, available at .next & .prev. see functions.php 
    if ($this->hasNextPage($page, $this->count, $this->posts_per_page)) {
      $next_requested_page = $page + 1;
      
      $link = $this->setPaginationLink($next_requested_page);
      
      $data['next'] = $link;
    }
    if ($this->hasPrevPage($page, $this->count, $this->posts_per_page)) {
      $prev_requested_page = $page - 1;
      
      $link = $this->setPaginationLink($prev_requested_page);
      
      $data['prev'] = $link;
    }
    
    // step 3 - all posts count divided by posts per page, rounded up to the highest integer
    $rounded = ceil($this->count / $this->posts_per_page);
    
    // step 4 - set the pagination pata. will be available at .pages
    $output = [];
    for ($i=0; $i < $rounded; $i++) {
    
      $offset = $i+1;
      
      // set the active class if req page matches
      if ($offset == $page) {
        $class = "uk-active";
      } else {
        $class = "not-active";
      }
      
      $new_link = $this->setPaginationLink($offset);
      
      // setting the data
      $output[] = array(
        'link' => $new_link, 
        'title' => $offset,
        'class' => $class,
      );
    }
    
    // available at .pages
    $data['pages'] = $output;
    
    // step 5 - return it all
    return $data;
  }
  
  protected function setPaginationLink($requested_page) {
    $query2 = ltrim($this->url, '/');
    $query3 = ltrim($query2, '?');
    parse_str($query3, $output);
    $output['p'] = $requested_page;
    $query_result = '/?'.http_build_query($output);
    
    return $query_result;
  }
  
}