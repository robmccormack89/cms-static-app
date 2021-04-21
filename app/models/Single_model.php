<?php
// https://github.com/nahid/jsonq
use Nahid\JsonQ\Jsonq;

class Single_model {
  
  public $slug;
  public $child_slug;
  
  public function __construct($slug, $child_slug) {
    $this->slug = $slug;
    $this->child_slug = $child_slug;
  }
  
  public function get_page() {
    $q = new Jsonq('../public/json/data.min.json');
    if ($this->child_slug) {
      $newslug = $this->slug.'/'.$this->child_slug;
      $page = $q->from('site.pages')->where('slug', '=', $newslug)->first();
    } else {
      $page = $q->from('site.pages')->where('slug', '=', $this->slug)->first();
    };
    return $page;
  }
  
  public function get_post() {
    $q = new Jsonq('../public/json/data.min.json');
    $post = $q->from('site.blog.posts')->where('slug', '=', $this->slug)->first();

    return $post;
  }
  
  public function get_project() {
    $q = new Jsonq('../public/json/data.min.json');
    $project = $q->from('site.portfolio.projects')->where('slug', '=', $this->slug)->first();

    return $project;
  }
  
}