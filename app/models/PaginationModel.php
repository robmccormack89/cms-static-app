<?php
namespace Rmcc;

class PaginationModel {
  
  public function __construct(int $count, string $url) 
  {
    $this->count = $count; 
    $this->url = $url; 
  }
  
  // if $count is greater than $posts_per_page, return the pagination data, else return null
  public function getPagination() {
    $data = null;
    if($this->count > $GLOBALS['_context']['per_page']) $data = $this->setPaginationData();
    return $data;
  }
  
  // set the pagination data
  protected function setPaginationData() {
    
    // step 1 - setup. data is blank. paged is the archive paging route. if req page is blank, set it to 1
    $data[] = '';
    
    // step 2 - if has next|prev, set the links data, available at .next & .prev
    if ($this->hasNextPage()) $data['next'] = $this->url.($GLOBALS['_context']['page'] + 1);

    if ($this->hasPrevPage()) $data['prev'] = $this->url.($GLOBALS['_context']['page'] - 1);
    
    // step 3 - all posts count divided by posts per page, rounded up to the highest integer
    $rounded = ceil($this->count / $GLOBALS['_context']['per_page']);
    
    // step 4 - set the pagination pata. will be available at .pages
    $output = [];
    for ($i=0; $i < $rounded; $i++) {
    
      $offset = $i+1;
      
      // set the active class if req page matches
      $class = "not-active";
      if ($offset == $GLOBALS['_context']['page']) $class = "uk-active";
      
      // setting the data
      $output[] = array(
        'link' => $this->url.$offset, 
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
  protected function hasNextPage() {
    if($GLOBALS['_context']['page'] >= $this->count / $GLOBALS['_context']['per_page']) return false;
    return true;
  }  
  protected function hasPrevPage() {
    if($GLOBALS['_context']['page'] > 1) return true;
    return false;
  }
  
}