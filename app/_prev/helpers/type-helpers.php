<?php
// these are all dependent on settings for the respective post types url routes
function is_blog() {
  $string = $_SERVER['REQUEST_URI'];
  $fresh = str_replace("/","",$string);
  $blog = $GLOBALS['configs']['blog_url'];
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
  $portfolio = $GLOBALS['configs']['portfolio_url'];
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
	$post = $GLOBALS['configs']['post_url'];
	$post_fresh = str_replace("/","",$post);
  if (strpos($fresh, $post_fresh) !== false) {
	   return true;
  }
}
function is_project() {
  $string = $_SERVER['REQUEST_URI'];
  $fresh = str_replace("/","",$string);
  $proj = $GLOBALS['configs']['project_url'];
  $proj_fresh = str_replace("/","",$proj);
  if (strpos($fresh, $proj_fresh) !== false) {
    return true;
  }
}
function is_category() {
  $string = $_SERVER['REQUEST_URI'];
  $fresh = str_replace("/","",$string);
  $proj = $GLOBALS['configs']['category_url'];
  $proj_fresh = str_replace("/","",$proj);
  if (strpos($fresh, $proj_fresh) !== false) {
    return true;
  }
}
function is_tag() {
  $string = $_SERVER['REQUEST_URI'];
  $fresh = str_replace("/","",$string);
  $proj = $GLOBALS['configs']['tag_url'];
  $proj_fresh = str_replace("/","",$proj);
  if (strpos($fresh, $proj_fresh) !== false) {
    return true;
  }
}