<?php
// redirect to https if site protocol is set to https
if ($configs['site_protocol'] == "https") {
  if ($_SERVER['HTTPS'] != 'on') {
    $url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('Location: ' . $url, true, 301);
    exit();
  }
}
// include the router class. see https://phprouter.com/ for further route setups & information
require_once('router.php');
// homepage route
$router->get('/', function(){
  $homepage = new Single_controller;
  $homepage->index();
});
// blog
$router->get($GLOBALS['configs']['blog_url'], function(){
  $blog = new Archive_controller('');
  $blog->blog('');
});
// if blog is set as paged, create the paged route
if($GLOBALS['configs']['is_blog_paged'] == true) {
  // blog with pagination
  $router->get($GLOBALS['configs']['blog_url'].'/page/:page', function($page){
    if ($page == 1) {
      $url = $GLOBALS['configs']['blog_url'];
      header('Location: ' . $url, true, 301);
      exit();
    }
    $blog = new Archive_controller($page);
    $blog->blog();
  });
}
if($GLOBALS['configs']['is_portfolio_paged'] == true) {
  // posts
  $router->get($GLOBALS['configs']['post_url'].'/:slug', function($slug){
    $post = new Single_controller;
    $post->post($slug);
  });
}
// portfolio
$router->get($GLOBALS['configs']['portfolio_url'], function(){
  $portfolio = new Archive_controller('');
  $portfolio->portfolio('');
});
// portfolio with pagination
$router->get($GLOBALS['configs']['portfolio_url'].'/page/:page', function($page){
  if ($page == 1) {
    $url = $GLOBALS['configs']['portfolio_url'];
    header('Location: ' . $url, true, 301);
    exit();
  }
  $portfolio = new Archive_controller($page);
  $portfolio->portfolio();
});
// projects
$router->get($GLOBALS['configs']['project_url'].'/:slug', function($slug){
  $project = new Single_controller;
  $project->project($slug, '');
});
// pages
$router->get('/:parent_slug', function($parent_slug){
  $page = new Single_controller;
  $page->page($parent_slug, '');
});
// pages/sub-pages
$router->get('/:parent_slug/:child_slug', function($parent_slug, $child_slug){
  $page = new Single_controller;
  $page->page($parent_slug, $child_slug);
});
// error page
$router->any('/404', function(){
  $core = new Core_controller;
  $core->error();
});