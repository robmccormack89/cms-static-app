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

/**
 * route for the homepage
 *
 */
$router->get('/', function(){
  $homepage = new Single_controller;
  $homepage->index();
});

/**
 * route for main content type archives (blog, portfolio)
 *
 */
$router->get($GLOBALS['configs']['blog_url'], function(){
  $blog = new Archive_controller();
  $blog->blog(null);
});
$router->get($GLOBALS['configs']['portfolio_url'], function(){
  $portfolio = new Archive_controller();
  $portfolio->portfolio(null);
});

/**
 * routes for taxonomy term archives (category|blog, tag|blog)
 *
 */
$router->get($GLOBALS['configs']['category_url'].'/:term', function($term){
  $category = new Archive_controller();
  $category->category($term, null);
});
$router->get($GLOBALS['configs']['tag_url'].'/:term', function($term){
  $tag = new Archive_controller();
  $tag->tag($term, null);
});

/**
 * if the blog is set as paged, do the paged routes
 *
 */
if($GLOBALS['configs']['is_blog_paged'] == true) {
  // route for main blog paged archive
  $router->get($GLOBALS['configs']['blog_url'].'/page/:page', function($page){
    // redirect requests for page one of paged archive to main archive
    if ($page == 1) {
      header('Location: ' . $GLOBALS['configs']['blog_url'], true, 301);
      exit();
    }
    $blog = new Archive_controller();
    $blog->blog($page);
  });
  // route for blog's paged taxonomy term archives
  $router->get($GLOBALS['configs']['category_url'].'/:term/page/:page', function($term, $page){
    // redirect requests for page one of paged archive to main archive
    if ($page == 1) {
      header('Location: '.$GLOBALS['configs']['category_url'].'/'.$term, true, 301);
      exit();
    }
    $category = new Archive_controller();
    $category->category($term, $page);
  });
}


/**
 * if the portfolio is set as paged, do the paged routes
 *
 */
if($GLOBALS['configs']['is_portfolio_paged'] == true) {
  // route for main portfolio paged archive
  $router->get($GLOBALS['configs']['portfolio_url'].'/page/:page', function($page){
    // redirect requests for page one of paged archive to main archive
    if ($page == 1) {
      header('Location: ' . $GLOBALS['configs']['portfolio_url'], true, 301);
      exit();
    }
    $portfolio = new Archive_controller();
    $portfolio->portfolio($page);
  });
}

/**
 * route for singular items (posts, projects, pages, pages/sub-pages)
 *
 */
$router->get($GLOBALS['configs']['post_url'].'/:slug', function($slug){
  $post = new Single_controller;
  $post->post($slug);
});
$router->get($GLOBALS['configs']['project_url'].'/:slug', function($slug){
  $project = new Single_controller;
  $project->project($slug, null);
});
$router->get('/:parent_slug', function($parent_slug){
  $page = new Single_controller;
  $page->page($parent_slug, null);
});
$router->get('/:parent_slug/:child_slug', function($parent_slug, $child_slug){
  $page = new Single_controller;
  $page->page($parent_slug, $child_slug);
});

// route for error pages (routes that aren't accounted for above)
$router->any('/404', function(){
  $core = new Core_controller;
  $core->error();
});