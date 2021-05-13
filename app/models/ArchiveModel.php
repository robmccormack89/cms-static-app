<?php
namespace Rmcc;
use Nahid\JsonQ\Jsonq;

// the model for getting data for a main index archive such as 'blog' or 'portfolio'
class ArchiveModel {
  
  public function __construct($type, $key, $paged, $page, $posts_per_page) {
    $this->type = $type;
    $this->key = $key;
    $this->paged = $paged;
    $this->page = $page;
    $this->posts_per_page = $posts_per_page;
  }

  // get the actual archive data in 3 parts: meta, posts & pagination
  public function getArchive() {
    $blog = $this->getArchiveMeta();
    $blog['posts'] = $this->getPosts();
    // $blog['pagination'] = $this->getPagination();
    
    return $blog;
  }
  
  // get the archive meta data
  protected function getArchiveMeta() {
    $q = new Jsonq('../public/json/data.min.json');
    $data = $q->from('site.content_types.'.$this->type.'.meta')->get();
    
    // pagination related - could be grouped into a pagination class
    if($this->page) {
      $data['title'] = $data['title'].' (Page '.$this->page.')';
    }
    
    return $data;
  }
  
  protected function getPosts() {
  
    // if paged is true
    if ($this->paged) {
      
      $posts = $this->getChunkedPosts();
      
      if ($this->getPagedPosts($posts)) {
        $data = $this->getPagedPosts($posts);
      } else {
        $data = false;
      }
      
    } else {
      $data = $this->getAllPosts();
    }
  
    return $data;
  }
  
  // get some chunked posts
  protected function getChunkedPosts() {
    $q = new Jsonq('../public/json/data.min.json');
    $posts = $q->from('site.content_types.'.$this->type.'.'.$this->key)
    ->chunk($this->posts_per_page);
    
    return $posts;
  }
  protected function getPagedPosts($posts) {

    if (!$this->page) {
      $page = 0;
    } else {
      $page = $this->page - 1;
    }

    if (!($this->posts_per_page * $page >= $this->getAllPostsCount())) {
      $data = PostsModel::setPostsTease($posts[$page], $this->key, '/'.$this->type);
    } else {
      $data = false;
    }
    
    return $data;
    
  }
  
  // get all posts (with posts_per_page setting still applied, will just return the number of posts set in Archive Model construction)
  protected function getAllPosts() {
    $q = new Jsonq('../public/json/data.min.json');
    $posts = $q->find('site.content_types.'.$this->type.'.'.$this->key)
    ->get();
    
    $index = '/'.$this->type;
    
    // $data = PostsModel::setPostsTeaseLink($posts, $this->key, $this->index);
    $data = PostsModel::setPostsTease($posts, $this->key, $index);
    
    return $data;
  }
  // get all posts count; this is used for checking against to determine pagination
  protected function getAllPostsCount() {
    $q = new Jsonq('../public/json/data.min.json');
    $posts = $q->from('site.content_types.'.$this->type.'.'.$this->key)
    ->get();
    $data = $posts->count();
  
    return $data;
  }
  
  
  
  
  
  
  
  
  
  
  
  
  // doesnt require anything from pagination variables but is used in pagination
   // has pagination
  protected function getPosts2() {
  
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
  protected function getPagination() {
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
  protected function getPagedPosts2($posts) {

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