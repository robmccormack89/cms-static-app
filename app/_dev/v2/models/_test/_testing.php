<?php
namespace Rmcc;

class QueryPostsModel {
      
  public function __construct($args, $default_posts) {
    $this->args = $args; // the arguments by which to modify the existing posts. this will be an array or a query string. but use static sets first
    $this->default_posts = $default_posts; // just a fallback for now. these are the original posts for the archive before modifying the query
    
    // the ideal properties this class will have; based on WP_QUERY https://developer.wordpress.org/reference/classes/wp_query/#properties
    $this->query = $this->static_args_string(); // Holds the query string that was passed to the query object
    $this->query_vars = $this->static_args_array(); // An associative array containing the dissected $query: an array of the query variables and their respective values.
    $this->queried_object = ''; // Can hold information on the requested category, author, post or Page, archive etc.,.
    $this->posts = $this->get_posts(); // Gets filled with the requested posts
    $this->post_count = $this->get_posts_per_page(); // The number of posts being displayed. per page
    $this->found_posts = (new Json)->count(); // The total number of posts found matching the current query parameters
    $this->max_num_pages = $this->get_posts_max_pages(); // The total number of pages. Is the result of $found_posts / $posts_per_page
    
    // $this->init(); // fired on QueryPostsModel object creation. this may be for test, or for setting defaults
  }
  
  public function init() {}
    
  public function get_posts() {
    
    $posts = $this->get_all_singles();
    $count = $posts->count();
    
    // set $q data location
    $q = new Json('../public/json/data.min.json');
    
    $_type = $this->type_key();
    $_search = $this->search_key();
    $_date = $this->date_key();
    $_tax_query = $this->tax_query_key();
    $_relation = $this->relation_key();
    $_name = $this->name_key();
    
    // type
    if($_type) {
      $type_plural = type_setting_by_key('single', $_type, 'items'); // returns 'posts' or 'projects' etc...
      $type_archive = type_setting_by_key('single', $_type, 'key'); // returns 'blog' or 'portfolio' etc...
      $_location = 'site.content_types.'.$type_archive.'.'.$type_plural;
      
      // set $location based on $_type
      if($_type == 'page') $_location = 'site.content_types.page';
      
      $posts = $q->from($_location);
    }
    
    // search
    if($_search) {
      // $posts = new Json($posts);
      $posts = $posts->copy();
      $posts = $posts
      ->where(function($query) use ($_search) {
        $query->where('excerpt', 'match', '(?i)'.$_search);
        $query->orWhere('title', 'match', '(?i)'.$_search);
      });
    }

    // date
    if($_date) {
    
      $_year = $this->year_key();
      $_month = $this->month_key();
      $_day = $this->day_key();
    
      $posts = new Json($posts);
    
      // year
      if($_year){
        $posts = $posts
        ->where(function($query) {
          $query->where('date_time', 'year', '2021');
        });
      }
    
      // month
      if($_month){
        $posts = $posts
        ->where(function($query) {
          $query->where('date_time', 'month', '04');
        });
      }
    
      // day
      if($_day){
        $posts = $posts
        ->where(function($query) {
          $query->where('date_time', 'day', '04');
        });
      }
    
    }
    
    // tax_query
    if($_tax_query) {
      if($_relation == 'OR') {
    
        /////////////////////////////////////
    
        // $posts = new Json($posts);
        // $posts = $posts
        // ->where(function($query) {
        //   // $query->where('categories', 'any', 'media')->orWhere('categories', 'any', 'events'); // operator 'IN'
        //   $query->where('categories', 'any', 'news');
        //   $query->where('categories', 'any', 'media'); // operator 'AND'
        // });
        // 
        // $posts = $posts
        // ->orWhere(function($query) {
        //   // $query->where('tags', 'any', 'twig')->orWhere('tags', 'any', 'javascript');  // operator 'IN'
        //   $query->where('tags', 'any', 'css');
        //   $query->where('tags', 'any', 'javascript');  // operator 'AND'
        // });
    
        /////////////////////////////////////
    
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
                    
                    // if ($op == 'AND') {
                    // 
                    //   // 'AND' = posts where each have ALL of the listed terms
                    //   $query->where($tax, 'any', $term); 
                    // 
                    // } elseif ($op == 'NOT IN') {
                    // 
                    //   // 'NOT IN' = posts where none of the listed terms exist
                    //   $query->where($tax, 'notany', $term); 
                    // 
                    // } else {
                    // 
                    //   // 'IN' = posts where each has at leats ONE of the listed terms
                    //   if ($first_query_iterator == 1) {
                    //     $query->where($tax, 'any', $term);
                    //   } else {
                    //     $query->orWhere($tax, 'any', $term);
                    //   }
                    // 
                    // }
                    
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
        
                    // 'AND' = posts where each have ALL of the listed terms
                    // if($op == 'AND'){
                    //   // $terms_query = $prev->where($tax, 'any', $term);
                    //   // operator 'AND'
                    //   // does a 'where' clause for all items
                    //   $query->where($tax, 'any', $term); 
                    // } 
                    // 
                    // // 'IN' = posts where each has at leats ONE of the listed terms
                    // if($op == 'IN') {
                    //   // $terms_query = $prev->orWhere($tax, 'any', $term);
                    //   // operator 'IN'
                    //   // for first item, does 'where' clause. subsequent items do a 'orWhere' clause
                    //   if ($next_query_iterator == 1) {
                    //     $query->where($tax, 'any', $term);
                    //   } else {
                    //     $query->orWhere($tax, 'any', $term);
                    //   }
                    // }
                    // 
                    // // 'NOT IN' = posts where none of the listed terms exist
                    // if($op == 'NOT IN') {
                    //   // $terms_query = $prev->where($tax, 'notany', $term);
                    //   $query->where($tax, 'notany', $term); 
                    // }
                    
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
    
        /////////////////////////////////////
    
      } else {
    
        foreach ($_tax_query as $key => $value) if (is_array($value)) {
          if(array_key_exists('taxonomy', $value)) $tax = tax_setting_by_key($type_archive, 'single', $value['taxonomy'], 'key');
          if(array_key_exists('terms', $value)) {
            if(array_key_exists('operator', $value)) $op = $value['operator'];
    
            $posts = new Json($posts);
            $terms = $value['terms'];
    
            if(is_array($value['terms'])){
              $posts = $posts
              ->where(function($query) use ($terms, $tax, $value) {
                $first_query_i = 0;
                foreach($terms as $term){
                  $first_query_iterator = ++$first_query_i;
    
                  if($value['operator'] == 'AND'){ // 'AND' = posts where each have ALL of the listed terms
                    $query->where($tax, 'any', $term); 
                  } 
    
                  if($value['operator'] == 'IN') { // 'IN' = posts where each has at leats ONE of the listed terms
                    if ($first_query_iterator == 1) {
                      $query->where($tax, 'any', $term);
                    } else {
                      $query->orWhere($tax, 'any', $term);
                    }
                  }
    
                  if($value['operator'] == 'NOT IN') { // 'NOT IN' = posts where none of the listed terms exist
                    $query->where($tax, 'notany', $term); 
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
    
    // slug
    if($_name) {
      // $posts = new Json($posts);
      $posts = $posts->copy();
      $posts = $posts
      ->where(function($query) use ($_name) {
        $query->where('slug', 'match', '(?i)'.$_name);
      });
    }
    
    return $posts->get();
  }
  
  public function type_key() {
    if(array_key_exists('type', $this->query_vars)) return $this->query_vars['type'];
    return false;
  }
  public function search_key() {
    if(array_key_exists('s', $this->query_vars)) return $this->query_vars['s'];
    return false;
  }
  public function date_key() {
    if(array_key_exists('date', $this->query_vars)) return $this->query_vars['date'];
    return false;
  }
  public function year_key() {
    $date = $this->date_key();
    if(array_key_exists('year', $date)) return $date['year'];
    return false;
  }
  public function month_key() {
    $date = $this->date_key();
    if(array_key_exists('month', $date)) return $date['month'];
    return false;
  }
  public function day_key() {
    $date = $this->date_key();
    if(array_key_exists('day', $date)) return $date['day'];
    return false;
  }
  public function tax_query_key() {
    if(array_key_exists('tax_query', $this->query_vars) && $this->type_key()) return $this->query_vars['tax_query'];
    return false;
  }
  public function relation_key() {
    $tax_query_array = $this->tax_query_key();
    if($tax_query_array){
      if(array_key_exists('relation', $tax_query_array)) {
        return $tax_query_array['relation'];
      }
    }
    return false;
  }
  public function name_key() {
    if(array_key_exists('name', $this->query_vars)) return $this->query_vars['name'];
    return false;
  }
  
  // statics
  private function static_args_array(){
    $args = array(
      
      // working. takes string & content type singular label (post, project etc...)
      'type' => 'post',
      
      // working. takes array with relation key & sub-arrays
      'tax_query' => array(
        'relation' => 'AND', // working: 'AND', 'OR'. Deaults to 'AND'
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
      's' => 'blah',
      
      // working. takes array with sub-array/s.
      // just one sub-array for now tho, containing year, month or day args
      // will look at mutiple date arrays later tho don't see the major point
      'date' => array(
        array(
          'year'  => 2021,
          'month' => 1,
          'day'   => 1,
        ),
      ),
      
      // working. takes string & searches in slugs for matches
      'name' => 'lorem-ipsum-dolor',
      
      // the pagination stuff. in progress...
      'per_page' => 5,
      'p' => 2,
      'show_all' => true
    );
    return $args;
  }
  private function static_args_string(){
    $args = 'type=post&categories=news,media&tags=twig,css&s=lorem&year=2021&month=1&day=1&per_page=5&p=2';
    return $args;
  }
  
  // get some property values
  private function get_posts_per_page() {
    $per_page = 4; //  set default value
    
    $per_page_setting_exists_somewhere = false; // test condition
    if($per_page_setting_exists_somewhere) $per_page = $per_page_setting; // test condition
    
    return $per_page;
  }
  private function get_posts_max_pages() {
    $max_pages = $this->found_posts / $this->post_count;
    return ceil($max_pages);
  }
  
  // gets all singles
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