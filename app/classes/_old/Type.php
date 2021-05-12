<?php
use Nahid\JsonQ\Jsonq;

class Type {
  
  public function __construct($type) {
    $this->type = $type;
  }
  
  // get post type settings (blog or portfolio)
  public function get_type_meta() {
    $q = new Jsonq('../public/json/data.min.json');
    $data = $q->from('site.'.$this->type.'.meta')->get();

    return $data;
  }
  public function get_type_taxonomies() {
    $q = new Jsonq('../public/json/data.min.json');
    $data = $q->from('site.'.$this->type.'.taxonomies')->get();

    return $data;
  }
  public function get_tax_terms($tax) {
    $q = new Jsonq('../public/json/data.min.json');
    $data = $q->from('site.'.$this->type.'.taxonomies.'.$tax.'')->get();

    return $data;
  }
  public function get_tax_term($tax, $term) {
    
    if($term == null) {
      
      $q = new Jsonq('../public/json/data.min.json');
      $data = $q->from('site.'.$this->type.'.meta')->get();
      
    } elseif($term) {
      
      $q = new Jsonq('../public/json/data.min.json');
      $data = $q->from('site.'.$this->type.'.taxonomies.'.$tax)
      ->where('slug', '=', $term)->first();
    }

    return $data;
  }


  // add new archive types & their json locations here (blog, portfolio)
  public function get_archive_locations() {

    if($this->type == 'blog' || $this->type == 'post') {
      $data = 'site.blog.posts';
    } elseif($this->type == 'portfolio' || $this->type == 'project') {
      $data = 'site.portfolio.projects';
    }

    return $data;
  }
  // add new archive types & their route functions here; requires new route functions per type (blog, portfolio)
  public function get_type_route() {
    if($this->type == 'blog'):
    $data = get_blog_route();

    elseif($this->type == 'portfolio'):
    $data = get_portfolio_route();
    endif;
    
    return $data;
  }
  // add new archive types & their post_route functions here; requires new post_route functions per type (blog, portfolio)
  public function get_type_post_route() {
    if($this->type == 'blog'):
    $data = get_post_route();

    elseif($this->type == 'portfolio'):
    $data = get_project_route();
    endif;
    
    return $data;
  }
  // add new taxonomies & their route functions here; requires new route functions per type (categories, tags)
  public function get_tax_post_route($tax) {
    if($tax == 'categories'):
    $data = get_category_route();

    elseif($tax == 'tags'):
    $data = get_tag_route();
    endif;
    
    return $data;
  }
  // add new taxonomies & their post_route functions here; requires new post_route functions per type (categories, tags)
  public function get_tax_route($tax) {
    if($tax == 'categories'):
    $data = get_category_route_base();

    elseif($tax == 'tags'):
    $data = get_tag_route_base();
    endif;
    
    return $data;
  }
  // set the collection page archive title; add new taxonomies here  (categories, tags)
  public function get_collection_title($tax, $page) {
    if($tax == 'categories') {
      $title = 'Categories';
    } elseif($tax == 'tags') {
      $title = 'Tags';
    }
    if(!$page) {
      $data = $title;
    } else {
      $data = $title.' (Page '.$page.')';
    }
    return $data;
  }

  public function is_blog() {
    $string = $_SERVER['REQUEST_URI'];
    $fresh = str_replace("/","",$string);
    $blog = $GLOBALS['configs']['blog_route'];
    $blog_fresh = str_replace("/","",$blog);
    if ($fresh == $blog_fresh) {
      return true;
    } elseif (strpos($fresh, $blog_fresh.'page') !== false) {
      return true;
    }
  }
  public function is_portfolio() {
    $string = $_SERVER['REQUEST_URI'];
    $fresh = str_replace("/","",$string);
    $portfolio = $GLOBALS['configs']['portfolio_route'];
    $portfolio_fresh = str_replace("/","",$portfolio);
    if ($fresh == $portfolio_fresh) {
      return true;
    } elseif (strpos($fresh, $portfolio_fresh.'page') !== false) {
      return true;
    }
  }
  public function is_post() {
  	$string = $_SERVER['REQUEST_URI'];
    $fresh = str_replace("/","",$string);
  	$post = $GLOBALS['configs']['post_route'];
  	$post_fresh = str_replace("/","",$post);
    if (strpos($fresh, $post_fresh) !== false) {
  	   return true;
    }
  }
  public function is_project() {
    $string = $_SERVER['REQUEST_URI'];
    $fresh = str_replace("/","",$string);
    $proj = $GLOBALS['configs']['project_route'];
    $proj_fresh = str_replace("/","",$proj);
    if (strpos($fresh, $proj_fresh) !== false) {
      return true;
    }
  }
  public function is_category() {
    $string = $_SERVER['REQUEST_URI'];
    $fresh = str_replace("/","",$string);
    $proj = $GLOBALS['configs']['category_route'];
    $proj_fresh = str_replace("/","",$proj);
    if (strpos($fresh, $proj_fresh) !== false) {
      return true;
    }
    
  }
  public function is_category_collection() {
    $req = $_SERVER['REQUEST_URI'];

    $url = get_blog_route_base().get_category_route_base();
    $paged_url = $url.'/page';

    if ($req == $url) {
      return true;
    } elseif (strpos($req, $paged_url) !== false) {
      return true;
    } else {
      return false;
    }
  }
  public function is_tag() {
    $string = $_SERVER['REQUEST_URI'];
    $fresh = str_replace("/","",$string);
    $proj = $GLOBALS['configs']['tag_route'];
    $proj_fresh = str_replace("/","",$proj);
    if (strpos($fresh, $proj_fresh) !== false) {
      return true;
    }
  }
  public function is_tag_collection() {
    $req = $_SERVER['REQUEST_URI'];

    $url = get_blog_route_base().get_tag_route_base();
    $paged_url = $url.'/page';

    if ($req == $url) {
      return true;
    } elseif (strpos($req, $paged_url) !== false) {
      return true;
    }
  }
  
}