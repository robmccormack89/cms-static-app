<?php
// https://github.com/nahid/jsonq
use Nahid\JsonQ\Jsonq;

class Single_model {
  
  public function get_single_by_slug($parent_slug, $child_slug) {
    $q = new Jsonq('../public/json/data.json');
    if ($child_slug) {
      $slug = $parent_slug.'/'.$child_slug;
      $page = $q->from('site.pages')->where('slug', '=', $slug)->first();
    } else {
      $page = $q->from('site.pages')->where('slug', '=', $parent_slug)->first();
    };
    return $page;
  }
  
  public function get_post_by_slug($slug) {
    $q = new Jsonq('../public/json/data.json');
    $post = $q->from('site.blog.posts')->where('slug', '=', $slug)->first();

    return $post;
  }
  
  public function get_project_by_slug($slug) {
    $q = new Jsonq('../public/json/data.json');
    $project = $q->from('site.portfolio.projects')->where('slug', '=', $slug)->first();

    return $project;
  }
  
}