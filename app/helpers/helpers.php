<?php

function generate_tease_post_links($someposts, $singular_url_setting) {
  foreach ($someposts as $post) {
    $post['link'] = $GLOBALS['configs']['base_url'].$singular_url_setting.'/'.$post['slug'];
    $posts[] = $post;
  }
  return $posts;
}

function set_pagination_data($blog_data, $requested_page, $posts_count) {
  
  $data[] = "";
  
  if (has_next($requested_page, $posts_count, $blog_data['posts_per_page'])) {
    if(!$requested_page) {
      $requested_page = 1;
    };
    $data['next'] = $requested_page+1;
  }
  if (has_prev($requested_page, $posts_count, $blog_data['posts_per_page'])) {
    $data['prev'] = $requested_page-1;
  }

  return $data;
}

function has_next($requested_page, $posts_count, $posts_per_page) {
  
  if (!$requested_page) {
    $requested_page = 0;
  } else {
    $requested_page = $requested_page - 1;
  }
  $real_req_page = $requested_page + 1;
  
  if($real_req_page > $posts_count / $posts_per_page) {
    return false;
  } else {
    return true;
  };

}

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
// check if obj is page post or project
function is_page_post_or_project($obj) {
	if ($obj['type'] == 'page' || $obj['type'] == 'post' || $obj['type'] == 'project') {
		return true;
	}
}
// check if request is for blog archive
function is_blog() {
	$string = $_SERVER['REQUEST_URI'];
  $fresh = str_replace("/","",$string);
	$blog = $GLOBALS['configs']['blog_url'];
	$blog_fresh = str_replace("/","",$blog);
	if ($fresh == $blog_fresh) {
		return true;
	}
}
// check if request is for portfolio archive
function is_portfolio() {
	$string = $_SERVER['REQUEST_URI'];
  $fresh = str_replace("/","",$string);
	$portfolio = $GLOBALS['configs']['portfolio_url'];
	$portfolio_fresh = str_replace("/","",$portfolio);
	if ($fresh == $portfolio_fresh) {
		return true;
	}
}
// check the darklight cookie seting & set some values
function darklight_cookie() {
	
	$dark_light_def = '';
	
	if(isset($_COOKIE['darklightswitch'])) {
		$dark_light_def = array(
			'body_class' => 'uk-light',
			'sun_link_show_hide' => '',
			'moon_link_show_hide' => 'hidden',
		);
	} elseif (!isset($_COOKIE['darklightswitch'])) {
		$dark_light_def = array(
			'body_class' => '',
			'sun_link_show_hide' => 'hidden',
			'moon_link_show_hide' => '',
		);    
	}
	
	return $dark_light_def;
	
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