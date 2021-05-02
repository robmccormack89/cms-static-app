<?php
/**
 * these functions work on there own as genuine helper functions
 *
*/

function generate_tease_post_links($posts, $url_setting) {  
  foreach ($posts as $post) {
    $post['link'] = $GLOBALS['configs']['base_url'].$url_setting.'/'.$post['slug'];
    
    if(isset($post['categories'])){
      foreach ($post['categories'] as &$value) {
        $value = array(
          'link' => $GLOBALS['configs']['category_url'].'/'.$value,
          'slug' => $value,
          'title' => get_term_title_from_slug($value, 'categories')
        );
      }
    }
    
    if(isset($post['tags'])){
      foreach ($post['tags'] as &$value) {
        $value = array(
          'link' => $GLOBALS['configs']['tag_url'].'/'.$value,
          'slug' => $value,
          'title' => get_term_title_from_slug($value, 'tags')
        );
      }
    }
    
    $data[] = $post;
  }
  return $data;
}
function generate_tease_term_links($posts, $url_setting) {
  foreach ($posts as $post) {
    $post['link'] = $GLOBALS['configs']['base_url'].$url_setting.'/'.$post['slug'];
  
    $data[] = $post;
  }
  return $data;
}
function set_pagination_data($blog_data, $req_page, $posts_count, $archive_url) {
  
  $data[] = "";
  
  $pre = $archive_url.'/page/';
  
  if(!$req_page) {
    $req_page = 1;
  }
  
  if (has_next($req_page, $posts_count, $blog_data['posts_per_page'])) {
    $next_requested_page = $req_page+1;
    $data['next'] = $pre.$next_requested_page;
  }
  $prev_requested_page = $req_page-1;
  if (has_prev($req_page, $posts_count, $blog_data['posts_per_page'])) {
    $data['prev'] = $pre.$prev_requested_page;
  }
  
  $howmany = ceil($posts_count / $blog_data['posts_per_page']);
  
  $out = [];
  
  for ($i=0; $i < $howmany; $i++) {

    if ($i+1 == $req_page) {
      $class = "uk-active";
    } else {
      $class = "not-active";
    }
  
    $offs = $i+1;
    
    $out[] = array(
      'link' => $pre.$offs, 
      'title' => $offs,
      'class' => $class,
    );
  }
  
  $data['pages'] = $out;

  return $data;
}

function is_cache_enabled() {
  if ($GLOBALS['configs']['php_cache'] == 'enable') {
    return true;
  }
}

function is_draft_allowed($reqPage) {
  if ($reqPage && $reqPage['status'] == 'draft' && $GLOBALS['configs']['visitor_ip'] == $GLOBALS['configs']['author_ip']) {
    return true;
  }
}
function is_published($reqPage) {
  if ($reqPage && $reqPage['status'] == 'published') {
    return true;
  }
}
function is_published_or_draft_allowed($reqPage) {
  if (is_draft_allowed($reqPage) || is_published($reqPage)) {
    return true;
  }
}

function has_next($req_page, $posts_count, $posts_per_page) {
  
  if (!$req_page) {
    $req_page = 0;
  } else {
    $req_page = $req_page - 1;
  }
  $real_req_page = $req_page + 1;
  
  if($real_req_page >= $posts_count / $posts_per_page) {
    return false;
  } else {
    return true;
  };

}
function has_prev($req_page, $posts_count, $posts_per_page) {
  
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

// minify helper for cache.php
function minify_output($buffer){
  $search = array('/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s');
  $replace = array('>','<','\\1');
  if (preg_match("/\<html/i",$buffer) == 1 && preg_match("/\<\/html\>/i",$buffer) == 1) {
    $buffer = preg_replace($search, $replace, $buffer);
  }
  return $buffer;
}

// get objects in array using key->value
function get_in_array( string $needle, array $haystack, string $column){
  $matches = [];
  foreach( $haystack as $item )  if( $item[ $column ] === $needle )  $matches[] = $item;
  return $matches;
}