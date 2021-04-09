<?php

class Archive_model {
  
  public $singles;
  
  public function __construct()
  {
    $this->singles = New Single_model;
  }
  
  public function get_archive()
  {
    // if is blog
    if (is_blog()) {
      // get the posts from the singles
      $blog_posts = get_in_array( 'post', $this->singles->get_singles(), 'type');
      // assign the post link key & value
      foreach ($blog_posts as $post) {
        $post['link'] = $GLOBALS['configs']['blog_url'].'/'.$post['slug'];
        $posts[] = $post;
      }
      // return archive data with the posts under archive.posts
      $data = array(
        "name" => "blog",
        "title" => "Blog",
        "description" => "This is my Blog",
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
      
    // if is portfolio
    } elseif (is_portfolio()) {
      // get the projects from the singles
      $portfolio = get_in_array( 'project', $this->singles->get_singles(), 'type');
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