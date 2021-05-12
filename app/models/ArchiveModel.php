<?php
namespace Rmcc;
use Nahid\JsonQ\Jsonq as Q;

class ArchiveModel {
  
  public $type; // string. e.g 'blog' or 'portfolio'
  public $key; // string. e.g 'posts' or 'projects'
  public $paged; // boolean. setting. whether or not the archive is paged.
  public $page; // int. requested paginated page number. passed on via routes
  public $posts_per_page; // int. setting. how many posts to show per page on this archive
  
  public function __construct($type, $key, $page, $paged, $posts_per_page) {
    $this->type = $type;
    $this->key = $key;
    $this->index = '/'.$type;
    $this->paged = $paged;
    $this->page = $page;
    $this->posts_per_page = $posts_per_page;
  }

  public function getArchive() {
    $blog = $this->getArchiveMeta();
    $blog['posts'] = $this->getAllPosts();
    // $blog['pagination'] = $this->getPagination();
    
    return $blog;
  }
  
  // done
  public function getArchiveMeta() {
    $q = new Q('../public/json/data.min.json');
    $data = $q->from('site.content_types.'.$this->type.'.meta')->get();
    
    // pagination related - could be grouped into a pagination class
    if($this->page) {
      $data['title'] = $data['title'].' (Page '.$this->page.')';
    }
    
    return $data;
  }
  
  // not pagination related
  public function getAllPosts() {
    $q = new Q('../public/json/data.min.json');
    $posts = $q->from('site.content_types.'.$this->type.'.'.$this->key)
    ->chunk($this->posts_per_page);
    
    if (!$this->page) {
      $page = 0;
    }
    
    $data = PostsModel::setPostsTease($posts[$page], $this->key, $this->index);
    
    return $data;
  }
  
  // public function get_all_posts() {
  //   $q1 = new Jsonq('../public/json/data.min.json');
  //   $res1 = $q1->find('site.blog.posts');
  //   $q2 = $q1->copy();
  //   $res2 = $q2->reset()->find('site.portfolio.projects');
  // 
  //   $array1 = json_decode($res1, true);
  //   $array2 = json_decode($res2, true);
  //   $merge = array_merge($array1, $array2);
  // 
  //   $json = new Nahid\JsonQ\Jsonq();
  //   $data = $json->collect($merge);
  // 
  //   return $data;
  // }
  
  // doesnt require anything from pagination variables but is used in pagination
  // public function getAllPostsCount() {
  //   $posts = $this->getAllPosts();
  //   $data = $posts->count();
  // 
  //   return $data;
  // }
  
   // has pagination
  public function getPosts() {
  
    $q = new Jsonq('../public/json/data.min.json');
    $posts = $q->from('site.content_types.'.$this->type.'.'.$this->key)
    ->chunk($this->posts_per_page);
  
    // pagination related - could be grouped into a pagination class
    if ($this->paged) {
  
      if($this->getPagedPosts($posts)) {
        $data = $this->getPagedPosts($posts);
      } else {
        $data = false;
      }
  
    // not pagination related
    } else {
      $data = $this->getAllPosts();
    }
  
    return $data;
  }
  // pagination related - could be grouped into a pagination class
  public function getPagination() {
    if($this->paged == true) {
  
      $data = set_pagination_data(
        $this->posts_per_page,
        $this->page, 
        $this->getAllPostsCount(),
        $this->index
      );
  
    } else {
      $data = null;
    }
    return $data;
  }
  // pagination related
  public function getPagedPosts($posts) {

    if (!$this->page) {
      $page = 0;
    } else {
      $page = $this->page - 1;
    }

    if (!($this->posts_per_page * $page >= $this->getAllPostsCount())) {
      $data = get_tease_data($posts[$page], $this->type);
    } else {
      $data = false;
    }
    
    return $data;
    
  }
  
}