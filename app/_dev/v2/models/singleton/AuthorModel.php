<?php
namespace Rmcc;

//  a singleton class, used for representation of single objects. see https://phpenthusiast.com/blog/the-singleton-design-pattern-in-php
class AuthorModel {

  private static $instance = null;
  private $q;

  private function __construct() {
    $q = new Json('../public/json/data.min.json');
    $this->q = $q->from('site.author')->get();
  }
  
  public static function init() {
    if(!self::$instance) {
      self::$instance = new AuthorModel();
    }
    return self::$instance;
  }
  
  public function getAuthor() {
    return $this->q;
  }
}