<?php
// https://github.com/nahid/jsonq
use Nahid\JsonQ\Jsonq;

class Archive_model {
  
  public function posts($page)
  {
    if (!isset($data)) $data = new stdClass();
    
    // blog archive data
    $q = new Jsonq('../public/json/data.json');
    $data->archive = $q->from('site.blog')->get();
    // blog posts data
    $query = new Jsonq('../public/json/data.json');
    $blog_posts = $query->find('site.blog.posts')->get();
    $number_of_posts = $query->count();
    $data->posts = $this->get_paginated_posts($page, $blog_posts, $number_of_posts);
    
    return $data;
  }
  
  public function get_paginated_posts($page, $blog_posts, $number_of_posts) {
    
    $blog_settings_query = new Jsonq('../public/json/data.json');
    $settings = $blog_settings_query->from('site.blog.routes')->get();
  
    $posts_pages = array_chunk(json_decode($blog_posts), $settings->posts_per_page);
    
    if ($settings->is_paged) {
      if (!$page) {
        $page = 0;
      } else {
        $page = $page - 1;
      }
      if (!($settings->posts_per_page * $page > $number_of_posts)) {
        $data = $this->generate_post_links($posts_pages[$page], $GLOBALS['configs']['post_url']);
      } else {
        $data = false;
      }
    } else {
      $data = $this->generate_post_links($blog_posts, $GLOBALS['configs']['post_url']);
    }
    
    return $data;
  }
  
  public function generate_post_links($someposts, $singular_url_setting) {
    foreach ($someposts as $post) {
      $post->link = $GLOBALS['configs']['base_url'].$singular_url_setting.'/'.$post->slug;
      $posts[] = $post;
    }
    return $posts;
  }
  
  public function projects()
  {
    $q = new Jsonq('../public/json/data.json');
    $portfolio = $q->from('site.portfolio')->get();

    return $portfolio;
  }
  
}