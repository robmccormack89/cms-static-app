<?php
namespace Rmcc;

// class for getting posts lists for archives
class QueryPostsModel {
  
  public function __construct(
    $paged = false, 
    $page = null, 
    $posts_per_page = 4, 
    $search = null, 
    $type = null, 
    $date = null, 
    $category = null, 
    $tag = null
  ) 
  {
    $this->paged = $paged;
    $this->page = $page;
    $this->posts_per_page = $posts_per_page;
    $this->search = $search;
    $this->type = $type;
    $this->date = $date;
    
    $this->category = $category; 
    $this->tag = $tag;
    
    // $this->key = $GLOBALS['config']['types'][$this->type]['items'];
    // if(isset($GLOBALS['config']['types'][$this->type]['taxes_in_meta'])){
    //   $this->taxonomies = $GLOBALS['config']['types'][$this->type]['taxes_in_meta'];
    // } else {
    //   $this->taxonomies = null;
    // }
    
    $query_posts = $this->getQueryPosts();
    $this->posts = $query_posts['data'];
    $this->all_count = $query_posts['count'];
  }
    
  //
  // MainIndexArchive specific methods
  //
  private function getQueryPosts() {
    
    $posts = $this->getAllQueryPosts();
    $count = $posts->count();
    
    if($this->type){
      
      $items = getTypeSettingBySettingKey('single', $this->type, 'items'); // returns 'posts' or 'projects'
      $type_key = getTypeSettingBySettingKey('single', $this->type, 'key'); // returns 'blog' or 'portfolio'
      
      $q1 = new Json('../public/json/data.min.json');
      $posts = $q1
      ->from('site.content_types.'.$type_key.'.'.$items)
      ->get();
      $count = $posts->count();
    }
    
    if($this->search) {
      // Json_model extends Jsonq, used for custom ConditionalFactory from qarray
      // reason for this is to use custom match conditional
      // so can use proper case insensitive regex
      $q2 = new Json($posts);
      $posts = $q2
      ->where('excerpt', 'match', '(?i)'.$this->search)
      ->orWhere('title', 'match', '(?i)'.$this->search)
      ->get();
      $count = $posts->count();
    }
    if($this->date) {
      $q5 = new Json($posts);
      $posts = $q5->whereContains('date_time', $this->date)->get();
      $count = $posts->count();
    }
    
    if($this->category) {
      $q3 = new Json($posts);
      $posts = $q3->where('categories', 'any', $this->category)->get();
      $count = $posts->count();
    }
    if($this->tag) {
      $q4 = new Json($posts);
      $posts = $q4->where('tags', 'any', $this->tag)->get();
      $count = $posts->count();
    }

    if ($this->paged){
      $q5 = new Json($posts);
      $paged_posts = $q5->chunk($this->posts_per_page);
      $posts = $this->getPagedQueryPosts($paged_posts, $count);
    }

    $data = $this->setPostsTease($posts);
    
    // return [$data, $count];
    return array('data' => $data, 'count' => $count);
    
    // return $data;
  }
  private function getAllQueryPosts() {
    $q1 = new Json('../public/json/data.min.json');
    $res1 = $q1->find('site.content_types.blog.posts');
    $q2 = $q1->copy();
    $res2 = $q2->reset()->find('site.content_types.portfolio.projects');
    
    $array1 = json_decode($res1, true);
    $array2 = json_decode($res2, true);
    $merge = array_merge($array1, $array2);
    
    $json = new Json();
    $data = $json->collect($merge);

    return $data;
  }
  private function getAllQueryPostsCount() {
    $data = $this->getAllQueryPosts();
    
    $count = $data->count();
    
    return $count;
  }
  
  //
  // general posts handling methods
  //
  // get the paged posts
  private function getPagedQueryPosts($posts, $count) {
    
    $data = false;

    if (!$this->page) {
      $offset = 0;
    } else {
      $offset = $this->page - 1;
    }
    
    if (!isset($posts[$offset])) {
       $posts[$offset] = null;
    }

    $data = $posts[$offset];
    
    return $data;
    
  }
  private function setPostsTease($posts) {// setting the Post's Tease data
    $linked_posts = PostsModel::setPostsTeaseLink($posts);
    $termed_posts = PostsModel::setPostsTeaseTerms($linked_posts);
    return $termed_posts;
  }
  
}