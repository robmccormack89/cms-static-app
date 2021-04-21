<?php
// https://github.com/nahid/jsonq
use Nahid\JsonQ\Jsonq;

class Settings_model {
  
  public function get_settings() {
    
    $q = new Jsonq('../public/json/data.min.json');
    $data = $q->from('site.config')->get();

    return $data;
  }
  // (cpts)
  public function get_blog_meta() {
    
    $q = new Jsonq('../public/json/data.min.json');
    $data = $q->from('site.blog.meta')->get();

    return $data;
  }
  // (cpts)
  public function get_blog_settings() {
    
    $q = new Jsonq('../public/json/data.min.json');
    $data = $q->from('site.blog.routes')->get();

    return $data;
  }
  // (cpts)
  public function get_portfolio_meta() {
    
    $q = new Jsonq('../public/json/data.min.json');
    $data = $q->from('site.portfolio.meta')->get();

    return $data;
  }
  // (cpts)
  public function get_portfolio_settings() {
    
    $q = new Jsonq('../public/json/data.min.json');
    $data = $q->from('site.portfolio.routes')->get();

    return $data;
  }

}