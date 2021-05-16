<?php
namespace Rmcc;
use Nahid\JsonQ\Jsonq;

// class for getting posts lists for archives
class PostsModel {
  
  public function __construct($type, $key, $taxonomies, $paged, $page, $posts_per_page, $tax = null, $term = null) {
    $this->type = $type;
    $this->key = $key;
    $this->taxonomies = $taxonomies;
    $this->paged = $paged;
    $this->page = $page;
    $this->posts_per_page = $posts_per_page;
    $this->tax = $tax;
    $this->term = $term;
    $this->posts = $this->getPosts();
    $this->all_count = $this->getCount();
  }
  
  private function getPosts() {
    if(!($this->tax)){
      $data = $this->getArchivePosts();
    } else {
      $data = $this->getTermArchivePosts();
    }
    return $data;
  }
  private function getCount() {
    if(!($this->tax)){
      $data = $this->getAllPostsCount();
    } else {
      $data = $this->getAllTermPostsCount();
    }
    return $data;
  }
  
  //
  // TaxTermArchive specific methods
  //
  
  private function getTermArchivePosts() {

    $data = $this->getAllTermPosts();
  
    // if paged is true
    if ($this->paged){
      $q = new Jsonq('../public/json/data.min.json');
      $posts = $q->from('site.content_types.'.$this->type.'.'.$this->key)
      ->where($this->tax, 'any', $this->term)
      ->chunk($this->posts_per_page);
      $data = $this->getPagedPosts($posts);
    } 
  
    // print_r($data);
    return $data;
  }
  private function getAllTermPostsCount() {
    $q = new Jsonq('../public/json/data.min.json');
    $posts = $q->from('site.content_types.'.$this->type.'.'.$this->key)
    ->where($this->tax, 'any', $this->term)
    ->get();
    $data = $posts->count();
  
    return $data;
  }
  private function getAllTermPosts() {
    $q = new Jsonq('../public/json/data.min.json');
    $posts = $q->from('site.content_types.'.$this->type.'.'.$this->key)
    ->where($this->tax, 'any', $this->term)
    ->get();
    
    $data = $this->setPostsTease($posts);
    
    return $data;
  }
  
  //
  // MainIndexArchive specific methods
  //
  
  private function getArchivePosts() {
    
    $data = $this->getAllPosts();
  
    // if paged is true
    if ($this->paged){
      $q = new Jsonq('../public/json/data.min.json');
      $posts = $q->from('site.content_types.'.$this->type.'.'.$this->key)
      ->chunk($this->posts_per_page);
      if($posts){
        $data = $this->getPagedPosts($posts);
      } else {
        $data = null;
      }
      
    } 
  
    return $data;
  }
  private function getAllPostsCount() {
    $q = new Jsonq('../public/json/data.min.json');
    $posts = $q->from('site.content_types.'.$this->type.'.'.$this->key)
    ->get();
    $data = $posts->count();
  
    return $data;
  }
  private function getAllPosts() {
    $q = new Jsonq('../public/json/data.min.json');
    $posts = $q->find('site.content_types.'.$this->type.'.'.$this->key)
    ->get();
    
    $data = $this->setPostsTease($posts);
    
    return $data;
  }
  
  //
  // general posts handling methods
  //
  
  // get the paged posts
  private function getPagedPosts($posts) {
    
    // defaults to false. so in ArchiveController we check if ['posts'] exists. if there is no posts, we throw 404
    $data = false;

    if (!$this->page) {
      $offset = 0;
    } else {
      // offset is coz we need to jump down if $page exists to make it work
      $offset = $this->page - 1;
    }

    if (!($this->posts_per_page * $offset >= $this->getCount())) {
      $data = $this->setPostsTease($posts[$offset], $this->key, '/'.$this->type);
    }
    
    return $data;
    
  }
  // setting the Post's Tease data
  private function setPostsTease($posts) {
    $linked_posts = $this->setPostsTeaseLink($posts, $this->key);
    $termed_posts = $this->setPostsTeaseTerms($linked_posts, $this->key);
    return $termed_posts;
  }
  // setting the Post's Tease Link data
  private function setPostsTeaseLink($posts) {
    foreach ($posts as $post) {
      $post['link'] = '/'.$this->type.'/'.$this->key.'/'.$post['slug'];
      $data[] = $post;
    }
    return $data;
  }
  // setting the Post's Tease Taxonomy Meta data
  private function setPostsTeaseTerms($posts) {
    foreach ($posts as $post) {
      if($this->taxonomies) {
        $taxonomies = $this->taxonomies;
        foreach($taxonomies as $tax) {
          if(isset($post[$tax])){
            $terms = $post[$tax];
            foreach ($terms as &$term) {
              $term = array(
                'link' => '/'.$this->type.'/'.$tax.'/'.$term,
                'slug' => $term,
                'title' => \getTermTitleFromSlug($this->type, $tax, $term)
              );
            }
            $post[$tax] = null;
            $new_posts[$tax] = $terms;
            $post['taxonomies'] = $new_posts;
          }
        }
      }
      $data[] = $post;
    }
    return $data;
  }
  
}