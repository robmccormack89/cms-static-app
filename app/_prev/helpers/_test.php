<?php
/**
 * these functions are in progress
 *
*/

// image resize 
function imResize($img, $w, $h) {

  $image_name =  '../public/img/' .$img. '.jpg';
  $image = imagecreatefromjpeg($image_name);
  $imgResized = imagescale($image , 600, 600);
  imagejpeg($imgResized, '../public/img/' .$img. '-' .$width. '-' .$height. '.jpg');

}
// image crop
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
    return $output_name;
  }

}