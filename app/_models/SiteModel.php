<?php
namespace Rmcc;

//  a singleton class, used for representation of single objects. see https://phpenthusiast.com/blog/the-singleton-design-pattern-in-php
class SiteModel {

  private static $instance = null;
  private $q;

  private function __construct() {
    $q = new Json('../public/json/data.min.json');
    $this->q = $q->from('site.meta')->get();
  }
  
  public static function init() {
    if(!self::$instance) {
      self::$instance = new SiteModel();
    }
    return self::$instance;
  }
  
  public function getSite() {
    return $this->q;
  }
}