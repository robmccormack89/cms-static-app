<?php

class Url {
  
  public function __construct() {
    // 
  }

  // returns /blog
  function get_blog_route_base() {
    $data = '/'.$GLOBALS['configs']['blog_route'];
    
    return $data;
  }
  // returns http://example.com/blog
  function get_blog_route() {
    $data = $GLOBALS['configs']['base_url'].'/'.$GLOBALS['configs']['blog_route'];
    
    return $data;
  }
  // returns /posts
  function get_post_route_base() {
    $data = '/'.$GLOBALS['configs']['post_route'];
    
    return $data;
  }
  // returns http://example.com/blog/posts
  function get_post_route() {
    $data = $GLOBALS['configs']['base_url'].'/'.$GLOBALS['configs']['blog_route'].'/'.$GLOBALS['configs']['post_route'];
    
    return $data;
  }
  // returns /categories
  function get_category_route_base() {
    $data = '/'.$GLOBALS['configs']['category_route'];
    
    return $data;
  }
  // returns http://example.com/blog/categories
  function get_category_route() {
    $data = $GLOBALS['configs']['base_url'].'/'.$GLOBALS['configs']['blog_route'].'/'.$GLOBALS['configs']['category_route'];
    
    return $data;
  }
  // returns /tags
  function get_tag_route_base() {
    $data = '/'.$GLOBALS['configs']['tag_route'];
    
    return $data;
  }
  // returns http://example.com/blog/tags
  function get_tag_route() {
    $data = $GLOBALS['configs']['base_url'].'/'.$GLOBALS['configs']['blog_route'].'/'.$GLOBALS['configs']['tag_route'];
    
    return $data;
  }
  // returns /portfolio
  function get_portfolio_route_base() {
    $data = '/'.$GLOBALS['configs']['portfolio_route'];
    
    return $data;
  }
  // returns http://example.com/portfolio
  function get_portfolio_route() {
    $data = $GLOBALS['configs']['base_url'].'/'.$GLOBALS['configs']['portfolio_route'];
    
    return $data;
  }
  // returns /projects
  function get_project_route_base() {
    $data = '/'.$GLOBALS['configs']['project_route'];
    
    return $data;
  }
  // returns http://example.com/portfolio/projects
  function get_project_route() {
    $data = $GLOBALS['configs']['base_url'].'/'.$GLOBALS['configs']['portfolio_route'].'/'.$GLOBALS['configs']['project_route'];
    
    return $data;
  }
  
}