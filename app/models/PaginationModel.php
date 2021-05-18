<?php
namespace Rmcc;

class PaginationModel {
  
  public function __construct($posts_per_page, $page, $count, $paged, $url) {
    $this->posts_per_page = $posts_per_page; // posts per page setting
    $this->page = $page; // requested page/slug value
    $this->count = $count; // all posts count
    $this->paged = $paged; // paged setting
    $this->url = $url; // base url for the archive, needed for pagination links to work
    
    $this->pagination = $this->getPagination();
  }
  
  private function getPagination() {
    if($this->paged == true) {
      $data = $this->setPaginationData();
    } else {
      $data = null;
    }
    return $data;
  }
  
  // set the pagination data
  private function setPaginationData() {
    
    if(!$this->page) {
      $page = 1;
    } else {
      $page = $this->page;
    }
    
    // step 1 - setup. data is blank. paged is the archive paging route. if req page is blank, set it to 1
    $data[] = "";
    $paged = $this->url.'/page/';
    
    // step 2 - if has next|prev, set the links data, available at .next & .prev. see functions.php 
    if ($this->hasNextPage($page, $this->count, $this->posts_per_page)) {
      $next_requested_page = $page + 1;
      $data['next'] = $paged.$next_requested_page;
    }
    if ($this->hasPrevPage($page, $this->count, $this->posts_per_page)) {
      $prev_requested_page = $page - 1;
      $data['prev'] = $paged.$prev_requested_page;
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
  private function hasNextPage() {
    
    if(!$this->page) {
      $page = 1;
    } else {
      $page = $this->page;
    }
    
    if($page >= $this->count / $this->posts_per_page) {
      return false;
    } else {
      return true;
    };

  }  
  private function hasPrevPage() {
    
    if(!$this->page) {
      $page = 1;
    } else {
      $page = $this->page;
    }
    
    if($page > 1) {
      return true;
    } else {
      return false;
    };

  }
  
}