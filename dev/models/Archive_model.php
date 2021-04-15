<?php

class Archive_model extends Core_model {
  
  public function __construct()
  {
    parent::__construct();
  }
  
  public function get_archive()
  {

    if (is_blog()) {
      // get the posts from the singles
      $blog_posts = get_in_array( 'post', $this->singles, 'type');
      // assign the post link key & value
      foreach ($blog_posts as $post) {
        $post['link'] = $GLOBALS['configs']['blog_url'].'/'.$post['slug'];
        $posts[] = $post;
      }
      // return archive data with the posts under archive.posts
      $data = array(
        "name" => "blog",
        "title" => "Articles",
        "description" => "Posts, tutorials, snippets, musings, notes, and everything else. The archive of everything I've written.",
        "meta_title" => "",
        "meta_description" => "",
        "featured_img" => array(
          "src" => "/public/img/stock.jpg",
          "title" => "My Image",
          "alt" => "Alt text",
          "caption" => "A lovely caption",
          "description" => "Something about the image",
        ),
        "posts" => $posts
      );
      return $data;
    };
    
    if (is_portfolio()) {
      // get the projects from the singles
      $portfolio = get_in_array( 'project', get_json_data('singles'), 'type');
      foreach ($portfolio as $post) {
        $post['link'] = $GLOBALS['configs']['portfolio_url'].'/'.$post['slug'];
        $posts[] = $post;
      }
      // return archive data with the projects under archive.posts
      return array(
        "name" => "portfolio",
        "title" => "Portfolio",
        "description" => "This is my Portfolio",
        "meta_title" => "",
        "meta_description" => "",
        "featured_img" => array(
          "src" => "/public/img/stock.jpg",
          "title" => "My Image",
          "alt" => "Alt text",
          "caption" => "A lovely caption",
          "description" => "Something about the image",
        ),
        "posts" => $posts
      );
      
    }
    
  }
  
}