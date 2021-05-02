<?php
// https://github.com/nahid/jsonq
use Nahid\JsonQ\Jsonq;

class Single_model {
  
  public $slug;
  
  public function __construct($slug) {
    $this->slug = $slug;
  }
  // (cpts)
  public function get_page() {
    $q = new Jsonq('../public/json/data.min.json');
    $page = $q->from('site.pages')->where('slug', '=', $this->slug)->first();
    
    return $page;
  }
  // (cpts)
  public function get_post() {
    $q = new Jsonq('../public/json/data.min.json');
    $post = $q->from('site.blog.posts')->where('slug', '=', $this->slug)->first();

    return $post;
  }
  // (cpts)
  public function get_project() {
    $q = new Jsonq('../public/json/data.min.json');
    $project = $q->from('site.portfolio.projects')->where('slug', '=', $this->slug)->first();

    return $project;
  }
  
}