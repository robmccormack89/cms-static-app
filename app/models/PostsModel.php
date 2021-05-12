<?php
namespace Rmcc;
use Nahid\JsonQ\Jsonq;

class PostsModel {
  
  public static function setPostsTease($posts, $key, $index) {
    $linked_posts = self::setPostsTeaseLink($posts, $key, $index);
    $termed_posts = self::setPostsTeaseTerms($linked_posts, $key, $index);
    return $termed_posts;
  }
  public static function setPostsTeaseLink($posts, $key, $index) {
    foreach ($posts as $post) {
      $post['link'] = $GLOBALS['config']['base_url'].$index.'/'.$key.'/'.$post['slug'];
      $data[] = $post;
    }
    return $data;
  }
  public static function setPostsTeaseTerms($posts, $key, $index) {
    foreach ($posts as $post) {
      if(isset($post['categories'])){
        foreach ($post['categories'] as &$value) {
          $value = array(
            'link' => '/blog/categories/'.$value,
            'slug' => $value,
            'title' => self::getTermTitleFromSlug('blog', 'categories', $value)
          );
        }
      }
      if(isset($post['tags'])){
        foreach ($post['tags'] as &$value) {
          $value = array(
            'link' => '/blog/tags/'.$value,
            'slug' => $value,
            'title' => self::getTermTitleFromSlug('blog', 'tags', $value)
          );
        }
      }
      $data[] = $post;
    }
    return $data;
  }
  public static function getTermTitleFromSlug($type, $tax, $slug) {
    $q = new Jsonq('../public/json/data.min.json');
    $term = $q->from('site.content_types.'.$type.'.taxonomies.'.$tax)
    ->where('slug', '=', $slug)
    ->first();
    return $term->title;
  }
  
}