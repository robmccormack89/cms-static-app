<?php
namespace Rmcc;
use Nahid\JsonQ\Jsonq;

// class for setting the tease data in posts lists
// methods made static as there may be many cases where its necessary to set the tease data thru sets of posts
// so no need to init full PostsModel class when using setPostsTease(). e.g PostsModel::setPostsTease($posts, $key, $index);
// taxonomy terms need something more dynamic than defining for each taxonomy in each type
class PostsModel {
  
  public static function setPostsTease($posts, $key, $index) {
    $linked_posts = self::setPostsTeaseLink($posts, $key, $index);
    $termed_posts = self::setPostsTeaseTerms($linked_posts, $key, $index);
    return $termed_posts;
  }
  protected static function setPostsTeaseLink($posts, $key, $index) {
    foreach ($posts as $post) {
      $post['link'] = $GLOBALS['config']['base_url'].$index.'/'.$key.'/'.$post['slug'];
      $data[] = $post;
    }
    return $data;
  }
  protected static function setPostsTeaseTerms($posts, $key, $index) {
    foreach ($posts as $post) {
      
      if(isset($post['categories'])){
        $categories = $post['categories'];
        foreach ($categories as &$value) {
          $value = array(
            'link' => '/blog/categories/'.$value,
            'slug' => $value,
            'title' => self::getTermTitleFromSlug('blog', 'categories', $value)
          );
        }
        $post['categories'] = $categories;
      }
      
      if(isset($post['tags'])){
        $tags = $post['tags'];
        foreach ($tags as &$value) {
          $value = array(
            'link' => '/blog/tags/'.$value,
            'slug' => $value,
            'title' => self::getTermTitleFromSlug('blog', 'tags', $value)
          );
        }
        $post['tags'] = $tags;
      }
      
      $data[] = $post;
    }
    return $data;
  }
  protected static function getTermTitleFromSlug($type, $tax, $slug) {
    $q = new Jsonq('../public/json/data.min.json');
    $term = $q->from('site.content_types.'.$type.'.taxonomies.'.$tax)
    ->where('slug', '=', $slug)
    ->first();
    return $term->title;
  }
  
}