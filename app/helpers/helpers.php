<?php
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

/**
 * generate the links data for post teases
 *
 * @param $someposts - a posts array to set the link data for
 * @param $singular_url_setting - the singular url setting for a content type
 * @return object|array the modified posts data with .link data set for each item
 */
function generate_tease_post_links($someposts, $singular_url_setting) {
  foreach ($someposts as $post) {
    $post['link'] = $GLOBALS['configs']['base_url'].$singular_url_setting.'/'.$post['slug'];
    $posts[] = $post;
  }
  return $posts;
}
/**
 * sets the pagination data for an archive, to be used in pagination markup
 *
 * @param $blog_data - pagination settings for a content type (is_paged, posts_per_page)
 * @param $requested_page - requested page value (an integer like 2, 3 or 4)
 * @param $posts_count - the count value of all the posts being used (an integer like 12, 13 or 14)
 * @param $archive_url - the content type's archive url (/blog, /portfolio)
 * @return object|array the modified pagination data with .next, .prev & .pages data added to .pagination
 */
function set_pagination_data($blog_data, $requested_page, $posts_count, $archive_url) {
  
  $data[] = "";
  
  $pre = $archive_url.'/page/';
  
  if(!$requested_page) {
    $requested_page = 1;
  }
  
  if (has_next($requested_page, $posts_count, $blog_data['posts_per_page'])) {
    $next_requested_page = $requested_page+1;
    $data['next'] = $pre.$next_requested_page;
  }
  $prev_requested_page = $requested_page-1;
  if (has_prev($requested_page, $posts_count, $blog_data['posts_per_page'])) {
    $data['prev'] = $pre.$prev_requested_page;
  }
  
  $howmany = ceil($posts_count / $blog_data['posts_per_page']);
  
  for ($i=0; $i < $howmany; $i++) {

    if ($i+1 == $requested_page) {
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
/**
 * check to see if a given archive paged page has a next link
 *
 * @param $requested_page - the paged page being requested (an integer like 2, 3 or 4)
 * @param $posts_count - the posts count of the archive in question (an integer like 2, 3 or 4)
 * @param $posts_per_page - the posts_per_page setting of the archive in question (an integer like 2, 3 or 4)
 * @return boolean yes if archive page has next page
 */
function has_next($requested_page, $posts_count, $posts_per_page) {
  
  if (!$requested_page) {
    $requested_page = 0;
  } else {
    $requested_page = $requested_page - 1;
  }
  $real_req_page = $requested_page + 1;
  
  if($real_req_page >= $posts_count / $posts_per_page) {
    return false;
  } else {
    return true;
  };

}
/**
 * check to see if a given archive paged page has a previous link
 *
 * @param $requested_page - the paged page being requested (an integer like 2, 3 or 4)
 * @param $posts_count - the posts count of the archive in question (an integer like 2, 3 or 4)
 * @param $posts_per_page - the posts_per_page setting of the archive in question (an integer like 2, 3 or 4)
 * @return boolean yes if archive page has previous page
 */
function has_prev($requested_page, $posts_count, $posts_per_page) {
  
  if (!$requested_page) {
    $requested_page = 0;
  } else {
    $requested_page = $requested_page - 1;
  }
  $real_req_page = $requested_page + 1;
  
  if($real_req_page > 1) {
    return true;
  } else {
    return false;
  };

}
/**
 * get the data (decoded) from a given json file in public/json
 *
 * @param $filename - the name of the file in question (a string like 'data')
 * @return object|array - returns the decoded data from the given file
 */
function get_json_data($filename) {
  $str = file_get_contents('../public/json/'.$filename.'.json');
  $data = json_decode($str, true); // decode the JSON into an associative array
  return $data;
}
// get objects in array using key->value
function get_in_array( string $needle, array $haystack, string $column){
  $matches = [];
  foreach( $haystack as $item )  if( $item[ $column ] === $needle )  $matches[] = $item;
  return $matches;
}
// check if given obj is pag,e post or a project. checks the type property of the given singular pobject
function is_page_post_or_project($obj) {
	if ($obj['type'] == 'page' || $obj['type'] == 'post' || $obj['type'] == 'project') {
		return true;
	}
}
// check if request is for blog archive using REQUEST_URI
function is_blog() {
	$string = $_SERVER['REQUEST_URI'];
  $fresh = str_replace("/","",$string);
	$blog = $GLOBALS['configs']['blog_url'];
	$blog_fresh = str_replace("/","",$blog);
	if ($fresh == $blog_fresh) {
		return true;
	}
}
// check if request is for portfolio archive using REQUEST_URI
function is_portfolio() {
	$string = $_SERVER['REQUEST_URI'];
  $fresh = str_replace("/","",$string);
	$portfolio = $GLOBALS['configs']['portfolio_url'];
	$portfolio_fresh = str_replace("/","",$portfolio);
	if ($fresh == $portfolio_fresh) {
		return true;
	}
}
// check is given page/post published
function is_published($reqPage) {
	if ($reqPage && $reqPage['status'] == 'published') {
		return true;
	}
}
// check is given page/post an allowed draft
function is_draft_allowed($reqPage) {
	if ($reqPage && $reqPage['status'] == 'draft' && $GLOBALS['configs']['visitor_ip'] == $GLOBALS['configs']['author_ip']) {
		return true;
	}
}
// check is given page/post published or an allowed draft
function is_published_or_draft_allowed($reqPage) {
	if (is_published($reqPage) || is_draft_allowed($reqPage)) {
		return true;
	}
}
// check is cache setting enabled
function is_cache_enabled() {
	if ($GLOBALS['configs']['php_cache'] == 'enable') {
		return true;
	}
}
// image resize (in progress)
function imResize($img, $w, $h) {

	$image_name =  '../public/img/' .$img. '.jpg';
	$image = imagecreatefromjpeg($image_name);
	$imgResized = imagescale($image , 600, 600);
	imagejpeg($imgResized, '../public/img/' .$img. '-' .$width. '-' .$height. '.jpg');

}
// image crop (in progress)
function imCropAspect($img, $width, $height) {
	
	if (!defined('WIDTHINPUT')) define('WIDTHINPUT', 400);
	
	$helo = $width;
	// define("FOO", $helo);
	if (!defined('FOO')) define('FOO', $helo);
	
	// The file
	$filepath = BASE_URL.$img;
	
	$info = new SplFileInfo($img);
	$filename = str_replace(".jpg", "", $info->getFilename());
	// var_dump($filename);
	// 
	// // Set a maximum height and width
	// $width = $w;
	// $height = $h;
	
	// Get new dimensions
	list($width_orig, $height_orig) = getimagesize($filepath);
	
	$ratio_orig = $width_orig/$height_orig;
	
	// 
	// $init_width = $width;
	
	if ($width/$height > $ratio_orig) {
		$newwqidth = $width;
	   $width = $height*$ratio_orig;
	} else {
	   $height = $width/$ratio_orig;
	}
	
	// Resample
	$image_p = imagecreatetruecolor($width, $height);
	$image = imagecreatefromjpeg($filepath);
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
	
	$output_name = '../public/img/' .$filename. '-' . $height*$ratio_orig . '.jpg';
	// Output
	imagejpeg($image_p, $output_name, 100);
	
	// var_dump($height*$ratio_orig);
	
	if (file_exists($output_name)) {
		return  $output_name;
	}

}