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
  $homepage = new Home_controller;
  $homepage->index();
});
$router->get('/blog', function(){
  $blog = new Archive_controller;
  $blog->the_archive();
});
// dynamic posts route
$router->get('/blog/:slug', function($slug){
  $post = new Single_controller;
  $post->any($slug, '');
});
// dynamic pages route (with sub pages)
$router->get('/:parent_slug', function($parent_slug){
  $page = new Single_controller;
  $page->any($parent_slug, '');
});
$router->get('/:parent_slug/:child_slug', function($parent_slug, $child_slug){
  $page = new Single_controller;
  $page->any($parent_slug, $child_slug);
});
// error page route (goes last)
$router->any('/404', function(){
  $core = new Core_controller;
  $core->error();
});