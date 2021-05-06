<?php

/*
Type helper/s - content_type & taxonomy specific helpers. Add new types/taxonomies here
*/

// add new single types & their json locations here (page, post, project)
function get_singles_location($type) {
  
  if($type == 'page'):
  $data = 'site.pages';

  elseif($type == 'post'):
  $data = 'site.blog.posts';
    
  elseif($type == 'project'):
  $data = 'site.portfolio.projects';
  endif;
  
  return $data;
  
}
// add new archive types & their json locations here (blog, portfolio)
function get_archive_locations($type) {

  if($type == 'blog' || $type == 'post') {
    $data = 'site.blog.posts';
  } elseif($type == 'portfolio' || $type == 'project') {
    $data = 'site.portfolio.projects';
  }

  return $data;
}
// add new archive types & their route functions here; requires new route functions per type (blog, portfolio)
function get_type_route($type) {
  if($type == 'blog'):
  $data = get_blog_route();

  elseif($type == 'portfolio'):
  $data = get_portfolio_route();
  endif;
  
  return $data;
}
// add new archive types & their post_route functions here; requires new post_route functions per type (blog, portfolio)
function get_type_post_route($type) {
  if($type == 'blog'):
  $data = get_post_route();

  elseif($type == 'portfolio'):
  $data = get_project_route();
  endif;
  
  return $data;
}
// add new taxonomies here; to be used within tease's on listings (categories, tags)
function get_tease_data($posts, $type) {
  foreach ($posts as $post) {
    $post['link'] = get_tease_url($type, $post);
    
    if(isset($post['categories'])){
      foreach ($post['categories'] as &$value) {
        $value = array(
          'link' => get_category_route().'/'.$value,
          'slug' => $value,
          'title' => get_term_title_from_slug($type, 'categories', $value)
        );
      }
    }
    
    if(isset($post['tags'])){
      foreach ($post['tags'] as &$value) {
        $value = array(
          'link' => get_tag_route().'/'.$value,
          'slug' => $value,
          'title' => get_term_title_from_slug($type, 'tags', $value)
        );
      }
    }
    
    $data[] = $post;
  }
  return $data;
}
// add new taxonomies & their route functions here; requires new route functions per type (categories, tags)
function get_tax_post_route($tax) {
  if($tax == 'categories'):
  $data = get_category_route();

  elseif($tax == 'tags'):
  $data = get_tag_route();
  endif;
  
  return $data;
}
// add new taxonomies & their post_route functions here; requires new post_route functions per type (categories, tags)
function get_tax_route($tax) {
  if($tax == 'categories'):
  $data = get_category_route_base();

  elseif($tax == 'tags'):
  $data = get_tag_route_base();
  endif;
  
  return $data;
}
// set the collection page archive title; add new taxonomies here  (categories, tags)
function get_collection_title($tax, $page) {
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

/*
URL & route helper/s. Add new types/taxonomies here
*/

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

/*
Conditional helper/s. Add new types/taxonomies here
*/

function is_blog() {
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
function is_portfolio() {
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
function is_post() {
	$string = $_SERVER['REQUEST_URI'];
  $fresh = str_replace("/","",$string);
	$post = $GLOBALS['configs']['post_route'];
	$post_fresh = str_replace("/","",$post);
  if (strpos($fresh, $post_fresh) !== false) {
	   return true;
  }
}
function is_project() {
  $string = $_SERVER['REQUEST_URI'];
  $fresh = str_replace("/","",$string);
  $proj = $GLOBALS['configs']['project_route'];
  $proj_fresh = str_replace("/","",$proj);
  if (strpos($fresh, $proj_fresh) !== false) {
    return true;
  }
}
function is_category() {
  $string = $_SERVER['REQUEST_URI'];
  $fresh = str_replace("/","",$string);
  $proj = $GLOBALS['configs']['category_route'];
  $proj_fresh = str_replace("/","",$proj);
  if (strpos($fresh, $proj_fresh) !== false) {
    return true;
  }
  
}
function is_category_collection() {
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
function is_tag() {
  $string = $_SERVER['REQUEST_URI'];
  $fresh = str_replace("/","",$string);
  $proj = $GLOBALS['configs']['tag_route'];
  $proj_fresh = str_replace("/","",$proj);
  if (strpos($fresh, $proj_fresh) !== false) {
    return true;
  }
}
function is_tag_collection() {
  $req = $_SERVER['REQUEST_URI'];

  $url = get_blog_route_base().get_tag_route_base();
  $paged_url = $url.'/page';

  if ($req == $url) {
    return true;
  } elseif (strpos($req, $paged_url) !== false) {
    return true;
  }
}

/*
Menu helper/s
*/

// traverse a given set of menu items, and add active classes if link is found in REQUEST_URI
function menu_active_classes($menu_items) {
  foreach ($menu_items as $k => &$item) {
    
    if ($_SERVER['REQUEST_URI'] == $menu_items[$k]['link']) {
      $menu_items[$k]['class'] = 'uk-active';
    }

    if(isset($menu_items[$k]['children'])){
      foreach ($menu_items[$k]['children'] as $key => &$value) {
        if ($_SERVER['REQUEST_URI'] == $value['link']) {
          $value['class'] = 'uk-active';
        }
      }
    }

  }
  return($menu_items);
}

/*
Page helper/s
*/

function is_single_allowed($page) {
  if($page) {
    if ($page['status'] == 'draft' && $GLOBALS['configs']['visitor_ip'] == $GLOBALS['configs']['author_ip']) {
      return true;
    } elseif($page['status'] == 'published') {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
}

/*
Post tease helper/s
*/

function get_tease_url($type, $item) {
  $data = get_type_post_route($type).'/'.$item['slug'];
  
  return $data;
}
function get_tease_term_url($tax, $item) {
  $data = get_tax_post_route($tax).'/'.$item['slug'];
  
  return $data;
}
function get_tease_term_data($terms, $tax) {
  foreach ($terms as $term) {
    $term['link'] = get_tease_term_url($tax, $term);
  
    $data[] = $term;
  }
  return $data;
}

/*
Pagination helper/s
*/

// set the pagination data
function set_pagination_data($posts_per_page, $req_page, $count, $url) {
  
  // step 1 - setup. data is blank. paged is the archive paging route. if req page is blank, set it to 1
  
  $data[] = "";
  $paged = $url.'/page/';
  
  if(!$req_page) {
    $req_page = 1;
  }
  
  // step 2 - if has next|prev, set the links data, available at .next & .prev. see functions.php 
  
  if (has_next($req_page, $count, $posts_per_page)) {
    $next_requested_page = $req_page+1;
    $data['next'] = $paged.$next_requested_page;
  }
  $prev_requested_page = $req_page-1;
  if (has_prev($req_page, $count, $posts_per_page)) {
    $data['prev'] = $paged.$prev_requested_page;
  }
  
  // step 3 - all posts count divided by posts per page, rounded up to the highest integer
  
  $rounded = ceil($count / $posts_per_page);
  
  // step 4 - set the pagination pata. will be available at .pages
  
  $output = [];
  
  for ($i=0; $i < $rounded; $i++) {
  
    $offset = $i+1;
    
    // set the active class if req page matches
    if ($offset == $req_page) {
      $class = "uk-active";
    } else {
      $class = "not-active";
    }
    
    // setting the data
    $output[] = array(
      'link' => $paged.$offset, 
      'title' => $offset,
      'class' => $class,
    );
  }
  
  // available at .pages
  $data['pages'] = $output;
  
  // step 5 - return it all

  return $data;
}
// conditionals for pagination
function has_next($req_page, $count, $posts_per_page) {
  
  if (!$req_page) {
    $req_page = 0;
  } else {
    $req_page = $req_page - 1;
  }
  $real_req_page = $req_page + 1;
  
  if($real_req_page >= $count / $posts_per_page) {
    return false;
  } else {
    return true;
  };

}
function has_prev($req_page, $count, $posts_per_page) {
  
  if (!$req_page) {
    $req_page = 0;
  } else {
    $req_page = $req_page - 1;
  }
  $real_req_page = $req_page + 1;
  
  if($real_req_page > 1) {
    return true;
  } else {
    return false;
  };

}

/*
Data requisite helper/s - helpers that fetch actual data from json
*/

use Nahid\JsonQ\Jsonq;
// gets the term title using a given term slug. requires $type, $tax & $slug
function get_term_title_from_slug($type, $tax, $slug) {
  $q = new Jsonq('../public/json/data.min.json');
  $term = $q->from('site.'.$type.'.taxonomies.'.$tax)
  ->where('slug', '=', $slug)
  ->first();
  
  return $term->title;
}

/*
General helper/s - generalized helper functions
*/

// remove & replace the slashes in the requested string with hyphens for use as a file name
function request_to_filename() {
  $req = $_SERVER["REQUEST_URI"];
  // strip character/s from end of string
  $string = rtrim($req, '/');
  // strip character/s from beginning of string
  $trimmed = ltrim($string, '/');
  
  $data = str_replace('/', '-', $trimmed);
  
  return $data;
}
function slug_to_filename($slug) {
  // strip character/s from end of string
  $string = rtrim($slug, '/');
  // strip character/s from beginning of string
  $trimmed = ltrim($string, '/');
  
  $data = str_replace('/', '-', $trimmed);
  
  return $data;
}

// get objects in array using key->value
function get_in_array( string $needle, array $haystack, string $column){
  $matches = [];
  foreach( $haystack as $item )  if( $item[ $column ] === $needle )  $matches[] = $item;
  return $matches;
}