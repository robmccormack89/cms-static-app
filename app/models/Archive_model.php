<?php

class Archive_model {
  
  public function the_archive()
  {
    
    if (is_blog()) {
      
      return $this->blog_archive();
      
    } elseif (is_portfolio()) {
      
      return $this->portfolio_archive();;
      
    }
    
  }
  public function blog_archive()
  {
    $blog = New Post_model;
    $blog_posts = $blog->the_posts();
    
    return array(
      "name" => "blog",
      "title" => "Blog",
      "description" => "This is my Blog",
      "posts" => $blog_posts
    );
  }
  public function portfolio_archive()
  {
    // nested array (like an array of post objects)
    $data = array(
      array(
        "name" => "portfolio",
        "title" => "Portfolio",
        "description" => "This is my portfolio"
      )
    );
    return $data;
  }
  
}