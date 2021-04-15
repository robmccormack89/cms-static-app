<?php
// https://github.com/nahid/jsonq
use Nahid\JsonQ\Jsonq;

class Settings_model {
  
  public function get_settings() {
    
    $q = new Jsonq('../public/json/data.json');
    $settings = $q->from('site.config')->get();

    return $settings;
  }
  
  public function get_blog_url() {
    
    $q = new Jsonq('../public/json/data.json');
    $name = $q->from('site.blog.name')->get();

    return $name;
  }
  
  public function get_portfolio_url() {
    
    $q = new Jsonq('../public/json/data.json');
    $name = $q->from('site.portfolio.name')->get();

    return $name;
  }
  
}