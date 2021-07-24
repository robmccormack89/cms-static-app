<?php
namespace Rmcc;

class QueryModel {
  
  /*
  *
  * Examples of inputs into this class
  *
  */
  protected static $_argsString = 'type=post&categories=news,media&tags=twig,css&s=lorem&year=2021&month=4&day=4&per_page=5&p=2';
  protected static $_argsArray = array(
    // working. takes string & content type singular label (post, project etc...)
    'type' => 'post',
    
    // working. takes array with relation key & sub-arrays
    'tax_query' => array(
      // relation is required for now as there are checks based the iteration of the first array in this sequence, which should be 2nd when relation exists
      'relation' => 'OR', // working: 'AND', 'OR'. Deaults to 'AND'.
      array(
        'taxonomy' => 'category', // working. takes taxonomy string
        'terms' => array('news', 'media'), // working. takes array
        'operator' => 'AND' // 'AND', 'IN', 'NOT IN'. 'IN' is default. AND means posts must have all terms in the array. In means just one.
      ),
      array(
        'taxonomy' => 'tag',
        'terms' => array('css', 'javascript'),
        'operator' => 'AND'
      ),
    ),
    
    // working. takes string & searches in title/excerpt for matches
    's' => 'lorem',
    
    // working. takes array with date arguments.
    'date' => array(
      'year'  => 2021,
      'month' => 4,
      'day'   => 4,
    ),
    
    // working. takes string & searches in slugs for matches
    'name' => 'lorem',
    
    // the pagination stuff. seems to be working...
    'per_page' => 3,
    'p' => 1,
    'show_all' => false
  );
      
  public function __construct(array $args) {
    /*
    *
    * Properties based on https://developer.wordpress.org/reference/classes/wp_query/#properties
    *
    */
    $this->query = self::$_argsString; // Holds the query string that was passed to the query object
    // $this->query_vars = self::$_argsArray;
    $this->query_vars = $args; // An associative array containing the dissected $query: an array of the query variables and their respective values.
    $this->queried_object = $this->get_queried_object(); // Can hold information on the requested category, author, post or Page, archive etc.,.
    $this->posts = $this->get_posts(); // Gets filled with the requested posts
    $this->post_count = $this->get_posts_per_page(); // The number of posts being displayed. per page
    $this->found_posts = $this->get_posts_count(); // The total number of posts found matching the current query parameters
    $this->max_num_pages = $this->get_posts_max_pages(); // The total number of pages. Is the result of $found_posts / $posts_per_page
    
    $this->init();
  }
  
  private function get_queried_object() {
    $_type = $this->type_key();
    $type = type_setting_by_key('single', $_type, 'key');
    
    $_p = $this->paged_key();
    
    if($_type && $this->paged_key()) {
      $data = (new QueriedObjectModel($type, $_p))->data;
    }
    
    return $data;
  }
  
  // on initialize, do this stuff...
  private function init() {
    // echo($this->queried_object.': the queried object');
    // echo('<hr>');
    // echo($this->found_posts.': posts found');
    // echo('<hr>');
    // echo($this->post_count.': per_page setting');
    // echo('<hr>');
    // echo($this->paged_key().': page we should be on');
    // echo('<hr>');
    // if($this->is_paged()){
    //   print_r('we are paged!!');
    //   echo('<hr>');
    // }
  }
  
  /*
  *
  * Posts Stuff
  *
  */
  private function get_posts_count() {
    $data = $this->get_posts_query();
    return $data['count'];
  }
  private function get_posts() {
    $data = $this->get_posts_query();
    return $data['posts'];
  }
    
  // process the query args array
  public function get_posts_query() {
    
    /*
    *
    * 1. GET ALL ITEMS & THEIR COUNT FIRST
    *
    */
    $posts = $this->get_all_singles();
    $count = $posts->count();
    
    /*
    *
    * 2. SET THE $q DATA LOCATION
    *
    */
    $q = new Json('../public/json/data.min.json');
    
    /*
    *
    * 3. GETTING SOME KEY VALUES & SETTING THEM AS VARIABLES TO BE USED BELOW
    *
    */
    $_type = $this->type_key();
    $_search = $this->search_key();
    $_date = $this->date_key();
    $_tax_query = $this->tax_query_key();
    $_relation = $this->relation_key();
    $_name = $this->name_key();
    $_per_page = $this->get_posts_per_page();
    
    /*
    *
    * 4. CONTENT TYPES
    *
    */
    if($_type) {
      $type_plural = type_setting_by_key('single', $_type, 'items'); // returns 'posts' or 'projects' etc...
      $type_archive = type_setting_by_key('single', $_type, 'key'); // returns 'blog' or 'portfolio' etc...
      $_location = 'site.content_types.'.$type_archive.'.'.$type_plural;
    
      // set $location based on $_type
      if($_type == 'page') $_location = 'site.content_types.page';
    
      $posts = $q->from($_location);
    }
    
    /*
    *
    * 5. SEARCH TERMS: TITLE/EXCERPT
    *
    */
    if($_search) {
      // $posts = new Json($posts);
      $posts = $posts->copy();
      $posts = $posts
      ->where(function($query) use ($_search) {
        $query->where('excerpt', 'match', '(?i)'.$_search);
        $query->orWhere('title', 'match', '(?i)'.$_search);
      });
    }

    /*
    *
    * 6. DATE: YEAR, MONTH, DAY
    *
    */
    if($_date) {
    
      $_year = $this->year_key();
      $_month = $this->month_key();
      $_day = $this->day_key();
    
      $posts = new Json($posts);
    
      // year
      if($_year){
        $posts = $posts
        ->where(function($query) use ($_year) {
          $query->where('date_time', 'year', $_year);
        });
      }
    
      // month
      if($_month){
        $posts = $posts
        ->where(function($query) use ($_month) {
          $query->where('date_time', 'month', $_month);
        });
      }
    
      // day
      if($_day){
        $posts = $posts
        ->where(function($query) use ($_day) {
          $query->where('date_time', 'day', $_day);
        });
      }
    
    }
    
    /*
    *
    * 7. TAXONOMY QUERIES
    *
    */
    if($_tax_query) {
      if($_relation == 'OR') {
    
        $taxes_i = 0;
        foreach($_tax_query as $key => $value){
    
          if (is_array($value)) {
            $taxes_iterator = ++$taxes_i;
            if(array_key_exists('taxonomy', $value)) $tax = tax_setting_by_key($type_archive, 'single', $value['taxonomy'], 'key');
            if(array_key_exists('terms', $value)) {
              if(array_key_exists('operator', $value)) $op = $value['operator'];  
    
              $terms = $value['terms'];
    
              if ($taxes_iterator == 1) {
    
                $posts = $posts
                ->where(function($query) use ($terms, $tax, $op) {
                  $first_query_i = 0;
                  foreach($terms as $term){
                    $first_query_iterator = ++$first_query_i;
                    switch ($op) {
                      case 'AND':
                        $query->where($tax, 'any', $term); 
                        break;
                      case 'NOT IN':
                        $query->where($tax, 'notany', $term);
                        break;
                      default:
                        $first_query_iterator == 1 ? $query->where($tax, 'any', $term) : $query->orWhere($tax, 'any', $term);
                    }
                  }
                });
    
              } else {
    
                $new_posts = $posts;
                $posts = $new_posts
                ->orWhere(function($query) use ($terms, $tax, $op) {
                  $next_query_i = 0;
                  foreach($terms as $term){
                    $next_query_iterator = ++$next_query_i;
                    switch ($op) {
                      case 'AND':
                        $query->where($tax, 'any', $term);
                        break;
                      case 'NOT IN':
                        $query->where($tax, 'notany', $term); 
                        break;
                      default:
                        $first_query_iterator == 1 ? $query->where($tax, 'any', $term) : $query->orWhere($tax, 'any', $term);
                    }
                  }
                });
    
              }
            }
          }
        }
    
      } else {
    
        foreach ($_tax_query as $key => $value) if (is_array($value)) {
          if(array_key_exists('taxonomy', $value)) $tax = tax_setting_by_key($type_archive, 'single', $value['taxonomy'], 'key');
          if(array_key_exists('terms', $value)) {
            if(array_key_exists('operator', $value)) $op = $value['operator'];
    
            $posts = new Json($posts);
            $terms = $value['terms'];
    
            if(is_array($value['terms'])){
              $posts = $posts
              ->where(function($query) use ($terms, $tax, $op) {
                $first_query_i = 0;
                foreach($terms as $term){
                  $first_query_iterator = ++$first_query_i;
                  switch ($op) {
                    case 'AND':
                      $query->where($tax, 'any', $term);
                      break;
                    case 'NOT IN':
                      $query->where($tax, 'notany', $term); 
                      break;
                    default:
                      $first_query_iterator == 1 ? $query->where($tax, 'any', $term) : $query->orWhere($tax, 'any', $term);
                  }
                }
              })
              ->get();
            } else {
              $posts = $first_query
              ->where(function($query) use ($terms, $tax) {
                $query->where($tax, 'any', $terms); 
              })
              ->get();
            }
    
          }
        }
    
      }
    }
    
    /*
    *
    * 8. SLUG
    *
    */
    if($_name) {
      $posts = new Json($posts);
      $posts = $posts
      ->where(function($query) use ($_name) {
        $query->where('slug', 'match', '(?i)'.$_name);
      });
    }
    
    /*
    *
    * 9 SET THE POSTS COUNT BASED ON THE REMAINING POSTS, AFTER ALL/ANY QUERIES HAVE BEEN MADE
    *
    */
    $_count = $posts->count();
    
    /*
    *
    * 10. IF is_paged, TAKE THE REMAINING POSTS & CHUNK THEM BASED ON PAGED SETTINGS, ELSE get() THE REMAINING POSTS AS THEY ARE
    *
    */
    if($this->is_paged()){
      $posts = new Json($posts);
      $paged_posts = $posts->chunk($_per_page);
      $posts = $this->get_paged_posts($paged_posts);
    } else {
      $posts = $posts->get();
    }
    
    /*
    *
    * 11. SET THE POST TEASES DATA
    *
    */
    $posts = $this->set_posts_teases_data($posts);
    
    /*
    *
    * 12. RETURN $data & $count
    *
    */
    return array('posts' => $posts, 'count' => $_count);
  }
  
  /*
  *
  * Posts Tease Stuff
  *
  */
  
  // setting the Post's Tease data
  private function set_posts_teases_data($posts) {
    $linked_posts = $this->set_post_teases_link_data($posts);
    $termed_posts = $this->set_post_teases_term_data($linked_posts);
    return $termed_posts;
  }
  private function set_post_teases_link_data($posts) {
    $data = null;
    if($posts){
      foreach ($posts as $post) {
        
        $post['link'] = '/'.type_setting_by_key('single', $post['type'], 'key').'/'.type_setting_by_key('single', $post['type'], 'items').'/'.$post['slug'];
        $data[] = $post;
      }
    }
    return $data;
  }
  private function set_post_teases_term_data($posts) {
    $data = null;
    if($posts){
      foreach ($posts as $post) {
        $type_key = type_setting_by_key('single', $post['type'], 'key'); // returns 'blog' or 'portfolio'
        $taxonomies = (isset($GLOBALS['config']['types'][$type_key]['taxes_in_meta'])) ? $GLOBALS['config']['types'][$type_key]['taxes_in_meta'] : null;
        if($taxonomies) {
          foreach($taxonomies as $tax) {
            if(isset($post[$tax])){
              $terms = $post[$tax];
              foreach ($terms as &$term) {
                $term = array(
                  'link' => '/'.$type_key.'/'.$tax.'/'.$term,
                  'slug' => $term,
                  'title' => term_title_from_slug($type_key, $tax, $term)
                );
              }
              $post[$tax] = null;
              $new_posts[$tax] = $terms;
              $post['taxonomies'] = $new_posts;
            }
          }
        }
        $data[] = $post;
      }
    }
    return $data;
  }
  
  /*
  *
  * Paged stuff
  *
  */
  
  private function is_paged() {
    if(!$this->show_all_key()) return true;
  }
  
  // return paged posts based on $p(paged page setting)
  // the first argument should be posts that have just been chunked using the chunk() Jsonq method
  // chunk() splits the posts into chunks; get_paged_posts() returns the posts starting on the correct page based on $p(paged page setting)
  // if show_all is true, it should negate any pagination settings like this
  private function get_paged_posts($posts) {
    $data = false;
    
    $p = $this->paged_key();
  
    $offset = $p ? $p - 1 : 0;
    
    if (!isset($posts[$offset])) $posts[$offset] = null;
  
    $data = $posts[$offset];
    
    return $data;
  }
  
  /*
  *
  * Properties Configuration
  *
  */

  // do some stuff with some properties
  private function get_posts_per_page() {
    $per_page = $this->per_page_key() ? $this->per_page_key() : 4;
    return $per_page;
  }
  private function get_posts_max_pages() {
    $max_pages = $this->found_posts / $this->post_count;
    return ceil($max_pages);
  }
  
  /*
  *
  * Various keys to check for
  *
  */
  private function type_key() {
    if(array_key_exists('type', $this->query_vars)) return $this->query_vars['type'];
    return false;
  }
  private function tax_query_key() {
    if(array_key_exists('tax_query', $this->query_vars) && $this->type_key()) return $this->query_vars['tax_query'];
    return false;
  }
  private function relation_key() {
    $tax_query_array = $this->tax_query_key();
    if($tax_query_array){
      if(array_key_exists('relation', $tax_query_array)) {
        return $tax_query_array['relation'];
      }
    }
    return false;
  }
  private function search_key() {
    if(array_key_exists('s', $this->query_vars)) return $this->query_vars['s'];
    return false;
  }
  private function name_key() {
    if(array_key_exists('name', $this->query_vars)) return $this->query_vars['name'];
    return false;
  }
  private function date_key() {
    if(array_key_exists('date', $this->query_vars)) return $this->query_vars['date'];
    return false;
  }
  private function year_key() {
    $date = $this->date_key();
    if(array_key_exists('year', $date)) return $date['year'];
    return false;
  }
  private function month_key() {
    $date = $this->date_key();
    if(array_key_exists('month', $date)) return $date['month'];
    return false;
  }
  private function day_key() {
    $date = $this->date_key();
    if(array_key_exists('day', $date)) return $date['day'];
    return false;
  }
  private function per_page_key() {
    if(array_key_exists('per_page', $this->query_vars)) return $this->query_vars['per_page'];
    return false;
  }
  private function paged_key() {
    if(array_key_exists('p', $this->query_vars)) return $this->query_vars['p'];
    return false;
  }
  private function show_all_key() {
    if(array_key_exists('show_all', $this->query_vars)) return $this->query_vars['show_all'];
    return false;
  }
  
  /*
  *
  * Gets all singles. this is to start the query minus any arguments....
  *
  */
  private function get_all_singles() {
    
    // all content types should be gotten dynamically but this will do for now
    
    $q1 = new Json('../public/json/data.min.json');
    $res1 = $q1->find('site.content_types.blog.posts'); // get posts
    $array1 = json_decode($res1, true);
    
    $q2 = $q1->copy();
    $res2 = $q2->reset()->find('site.content_types.portfolio.projects'); // get projects
    $array2 = json_decode($res2, true);
    
    $q3 = $q1->copy();
    $res3 = $q3->reset()->find('site.content_types.page'); // get pages
    $array3 = json_decode($res3, true);

    $merge = array_merge($array1, $array2, $array3); // merge the different content types
    
    $data = (new Json())->collect($merge); // create a new Json object with the merged content types

    return $data;
  }
  
}