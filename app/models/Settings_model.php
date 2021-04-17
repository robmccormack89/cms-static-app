<?php
// https://github.com/nahid/jsonq
use Nahid\JsonQ\Jsonq;

class Settings_model {
  
  public function get_settings() {
    
    $q = new Jsonq('../public/json/data.json');
    $settings = $q->from('site.config')->get();

    return $settings;
  }
  
  public function get_blog_settings() {
    
    $q = new Jsonq('../public/json/data.json');
    $blog = $q->from('site.blog.routes')->get();

    return $blog;
  }
  
  public function get_portfolio_settings() {
    
    $q = new Jsonq('../public/json/data.json');
    $portfolio = $q->from('site.portfolio.routes')->get();

    return $portfolio;
  }

}