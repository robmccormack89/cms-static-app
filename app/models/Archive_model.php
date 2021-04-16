<?php
// https://github.com/nahid/jsonq
use Nahid\JsonQ\Jsonq;

class Archive_model {
  
  public function posts($page)
  {
    if (!isset($data)) $data = new stdClass();
    // blog archive data
    $query = new Jsonq('../public/json/data.json');
    $data->blog_archive = $query->from('site.blog')->get();
    reset($query);
    // blog routes(settings) data
    $query = new Jsonq('../public/json/data.json');
    $data->blog_settings = $query->from('site.blog.routes')->get();
    reset($query);
    // blog posts data
    $query = new Jsonq('../public/json/data.json');
    $data->blog_posts = $query->find('site.blog.posts')->get();
    // count all the posts
    $number_of_posts = $query->count();
    // chunk the posts(after json_decode) according to posts_per_page setting
    $posts_pages = array_chunk(json_decode($data->blog_posts), $data->blog_settings->posts_per_page);

    // if blog is paged at all, do the paged stuff
    if ($data->blog_settings->is_paged) {
      
      // if page is empty, set to zero(if is normal blog archive for example), else set it to its normal value minus one (offset necessary)
      if (!$page) {
        $page = 0;
      } else {
        $page = $page - 1;
      }

      // setting the posts data
      if (!($data->blog_settings->posts_per_page * $page > $number_of_posts)) {
        // if posts per page * requested page is greater than count of all posts,
        // data->posts is set from the paged post chunks
        $data->posts = json_decode(json_encode($posts_pages[$page]), true);    // So you can see it ;)
      } else {
        // else it is set as false, so we can check against it in the controller and render error(404) when it doesnt exist
        $data->posts = false;
      }
    } else {
    // else if blog is not set as paged, return all the posts as the blog posts
    // setting the posts data
     return $data->posts;
   }
    // return all the data
    return $data;
  }
  
  public function projects()
  {
    $q = new Jsonq('../public/json/data.json');
    $portfolio = $q->from('site.portfolio')->get();

    return $portfolio;
  }
  
}