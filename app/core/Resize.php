<?php
namespace Rmcc;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Point;

class Resize {

  private $imagine;
  
  private $w, $h, $crop;

  public function __construct($w, $h, $crop) {
    $this->imagine = new Imagine();
    $this->w = $w;
		$this->h = $h;
    $allowed_crop_positions = array(
      'default', 'center', 'top', 'bottom', 'left', 'right', 'top-center', 'bottom-center'
    );
    if ( $crop !== false && !in_array($crop, $allowed_crop_positions) ) {
      $crop = $allowed_crop_positions[0];
		}
		$this->crop = $crop;
  }
  
  /**
	 * @param    string    $src_filename     the basename of the file (ex: my-awesome-pic.jpg) (include extension for now)
	 * @param    string    $src_extension    the extension (ex: .jpg) UNUSED FOR NOW
	 * @return   string    the new filename to be used (ex: my-awesome-pic-300x200_cropped.jpg)
   *
   * edit to allow for external urls being used & saved etc
	 */
  public function filename($src_filename, $src_extension = 'jpg') {
    
    $width_string = round($this->w, 0);
    if(!$this->h) {
      $new_string = '-'.$width_string;
    } else {
      $height_string = round($this->h, 0);
      $new_string = '-'.$width_string.'x'.$height_string.'_cropped';
    }
    
    $new_filename =  substr_replace($src_filename , $new_string , strrpos($src_filename, '.'), 0);
    
    return $new_filename;
  }
  
  public function run($load_filename, $save_filename) {
    
    list($iwidth, $iheight) = getimagesize($load_filename); // get some data from original img with getimagesize() & save as variables using list()
    $ratio = $iwidth / $iheight; // original img ratio
    $photo = $this->imagine->open($load_filename); // open the given file
    
    // no height given; maintain aspect ratio
    if(!$this->h) {
      $height = $this->w / $ratio;
      $new_photo = $photo->resize(new Box($this->w, $height)); // resize the file using the new width & height... (maintain aspect)
    } 
    
    // height given, cropped, centered only for now
    // to do: setup to use the allowed_crop_positions & default to center where $crop is null. 
    if($this->h) {
      $size = new Box($iwidth, $iheight);
      $center_x = (new Point\Center($size))->getX();
      $center_y = (new Point\Center($size))->getY();
      if($center_x > ($iwidth - $this->w)) {
        $center_x = ($iwidth - $this->w) / 2;
        if($center_x < 0) $center_x = 0;
      }
      if($center_y > ($iheight - $this->h)) {
        $center_y = ($iheight - $this->h) / 2;
        if($center_y < 0) $center_y = 0;
      }
      $new_photo = $photo->crop(new Point($center_x, $center_y), new Box($this->w, $this->h)); // (hard cropped & centered)
    }
      
    $new_photo->save($save_filename); // then save it with the new filename
    
  }
    
  
}