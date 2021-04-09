<?php

class Archive_model {
  
  public $singles;
  
  public function __construct()
  {
    $this->singles = New Single_model;
  }
  
  public function archive_data()
  {
    // if is blog
    if (is_blog()) {
      // get the posts from the singles
      $blog_posts = get_in_array( 'post', $this->singles->the_singles(), 'type');
      // return archive data with the posts under archive.posts
      return array(
        "name" => "blog",
        "title" => "Blog",
        "description" => "This is my Blog",
        "posts" => $blog_posts
      );
    // if is portfolio
    } elseif (is_portfolio()) {
      // get the projects from the singles
      $portfolio = get_in_array( 'project', $this->singles->the_singles(), 'type');
      // return archive data with the projects under archive.posts
      return array(
        "name" => "portfolio",
        "title" => "Portfolio",
        "description" => "This is my Portfolio",
        "posts" => $portfolio
      );
    }
  }
}