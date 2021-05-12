<?php
use Nahid\JsonQ\Jsonq;

class Tease {
  
  public function get_tease_data($posts, $type) {
    foreach ($posts as $post) {
      $post['link'] = get_tease_url($type, $post);
      
      if(isset($post['categories'])){
        foreach ($post['categories'] as &$value) {
          $value = array(
            'link' => get_category_route().'/'.$value,
            'slug' => $value,
            'title' => get_term_title_from_slug($type, 'categories', $value)
          );
        }
      }
      
      if(isset($post['tags'])){
        foreach ($post['tags'] as &$value) {
          $value = array(
            'link' => get_tag_route().'/'.$value,
            'slug' => $value,
            'title' => get_term_title_from_slug($type, 'tags', $value)
          );
        }
      }
      
      $data[] = $post;
    }
    return $data;
  }
  
  public function get_tease_url($type, $item) {
    $data = get_type_post_route($type).'/'.$item['slug'];
    
    return $data;
  }
  
  public function get_term_title_from_slug($type, $tax, $slug) {
    $q = new Jsonq('../public/json/data.min.json');
    $term = $q->from('site.'.$type.'.taxonomies.'.$tax)
    ->where('slug', '=', $slug)
    ->first();
    return $term->title;
  }
  
  public function get_tease_term_data($terms, $tax) {
    foreach ($terms as $term) {
      $term['link'] = get_tease_term_url($tax, $term);
    
      $data[] = $term;
    }
    return $data;
  }
  
  public function get_tease_term_url($tax, $item) {
    $data = get_tax_post_route($tax).'/'.$item['slug'];
    
    return $data;
  }
  
}