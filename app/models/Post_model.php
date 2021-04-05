<?php

class Post {

  // some static pages from the json file
  public function the_posts()
  {
    $str = file_get_contents('../public/json/posts.json');
    $data = json_decode($str, true); // decode the JSON into an associative array
    return $data;
  }
  
  public function get_post_by_slug($slug) {
    $thePosts = $this->the_posts();
    $i = array_search($slug, array_column($thePosts, 'slug'));
    $element = ($i !== false ? $thePosts[$i] : null);
    return $element;
  }
  
}