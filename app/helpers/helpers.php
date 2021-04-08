<?php
// include the config file
$configs = include('../app/config/config.php');
// check the darklight cookie seting & set some values
function darklight_cookie_check() {
	
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
function is_page_published($reqPage) {
	if ($reqPage && $reqPage['status'] == 'published') {
		return true;
	}
}
// check is given page/post an allowed draft
function is_page_draft_allowed($reqPage) {
	if ($reqPage && $reqPage['status'] == 'draft' && $GLOBALS['configs']['visitor_ip'] == $GLOBALS['configs']['author_ip']) {
		return true;
	}
}
// check is given page/post published or an allowed draft
function is_published_or_author_draft($reqPage) {
	if (is_page_published($reqPage) || is_page_draft_allowed($reqPage)) {
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