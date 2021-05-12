<?php

class Pagination {
  
  public function __construct() {
  }
  
  // set the pagination data
  public function set_pagination_data($posts_per_page, $req_page, $count, $url) {
    
    // step 1 - setup. data is blank. paged is the archive paging route. if req page is blank, set it to 1
    
    $data[] = "";
    $paged = $url.'/page/';
    
    if(!$req_page) {
      $req_page = 1;
    }
    
    // step 2 - if has next|prev, set the links data, available at .next & .prev. see functions.php 
    
    if (has_next($req_page, $count, $posts_per_page)) {
      $next_requested_page = $req_page+1;
      $data['next'] = $paged.$next_requested_page;
    }
    $prev_requested_page = $req_page-1;
    if (has_prev($req_page, $count, $posts_per_page)) {
      $data['prev'] = $paged.$prev_requested_page;
    }
    
    // step 3 - all posts count divided by posts per page, rounded up to the highest integer
    
    $rounded = ceil($count / $posts_per_page);
    
    // step 4 - set the pagination pata. will be available at .pages
    
    $output = [];
    
    for ($i=0; $i < $rounded; $i++) {
    
      $offset = $i+1;
      
      // set the active class if req page matches
      if ($offset == $req_page) {
        $class = "uk-active";
      } else {
        $class = "not-active";
      }
      
      // setting the data
      $output[] = array(
        'link' => $paged.$offset, 
        'title' => $offset,
        'class' => $class,
      );
    }
    
    // available at .pages
    $data['pages'] = $output;
    
    // step 5 - return it all

    return $data;
  }
  
  // conditionals for pagination
  public function has_next($req_page, $count, $posts_per_page) {
    
    if (!$req_page) {
      $req_page = 0;
    } else {
      $req_page = $req_page - 1;
    }
    $real_req_page = $req_page + 1;
    
    if($real_req_page >= $count / $posts_per_page) {
      return false;
    } else {
      return true;
    };

  }
  
  public function has_prev($req_page, $count, $posts_per_page) {
    
    if (!$req_page) {
      $req_page = 0;
    } else {
      $req_page = $req_page - 1;
    }
    $real_req_page = $req_page + 1;
    
    if($real_req_page > 1) {
      return true;
    } else {
      return false;
    };

  }
  
}