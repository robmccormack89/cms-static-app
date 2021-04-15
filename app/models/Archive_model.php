<?php
// https://github.com/nahid/jsonq
use Nahid\JsonQ\Jsonq;

class Archive_model {
  
  public function posts()
  {
    $q = new Jsonq('../public/json/data.json');
    $blog = $q->from('site.blog')->get();

    return $blog;
  }
  
  public function projects()
  {
    $q = new Jsonq('../public/json/data.json');
    $portfolio = $q->from('site.portfolio')->get();

    return $portfolio;
  }
  
}