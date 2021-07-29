<?php
namespace Rmcc;

class PaginationModel {
  
  public function __construct(int $count) {
    $this->count = $count;
  }
  
  // if $count is greater than $posts_per_page, return the pagination data, else return null
  public function getPagination() {
    $data = null;
    if($this->count > $GLOBALS['_context']['per_page']) $data = $this->setPaginationData();
    return $data;
  }
  
  protected function setPaginationLink($new_part) {
    $current_url_parsed = parse_url($_SERVER['REQUEST_URI']);
    if(isset($current_url_parsed['query'])){
      parse_str($current_url_parsed['query'], $queryArray);
      $queryArray['p'] = $new_part;
      $newQueryStr = http_build_query($queryArray);
      $newQueryStr = str_replace("%2C", ",", $newQueryStr);
      $url = '?'.$newQueryStr;
    } else {
      $url = '?p='.$new_part;
    }
    return $url;
  }
  
  // set the pagination data
  protected function setPaginationData() {
    
    // step 1 - setup. data is blank. paged is the archive paging route. if req page is blank, set it to 1
    $data[] = '';
    
    // parse the url
    $current_url_parsed = parse_url($_SERVER['REQUEST_URI']);
    
    // step 2 - if has next|prev, set the links data, available at .next & .prev
    if ($this->hasNextPage()) {
      $nexturl = $this->setPaginationLink(($GLOBALS['_context']['page'] + 1));
      $data['next'] = $nexturl;
    };

    if ($this->hasPrevPage()) {
      $prevurl = $this->setPaginationLink(($GLOBALS['_context']['page'] - 1));
      $data['prev'] = $prevurl;
    };
    
    // step 3 - all posts count divided by posts per page, rounded up to the highest integer
    $rounded = ceil($this->count / $GLOBALS['_context']['per_page']);
    
    // step 4 - set the pagination pata. will be available at .pages
    $output = [];
    for ($i=0; $i < $rounded; $i++) {
    
      $offset = $i+1;
      
      // set the active class if req page matches
      $class = "not-active";
      if ($offset == $GLOBALS['_context']['page']) $class = "uk-active";
      
      $offseturl = $this->setPaginationLink($offset);
      // setting the data
      $output[] = array(
        'link' => $offseturl, 
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