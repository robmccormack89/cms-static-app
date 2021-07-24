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
    $this->found_posts = $this->get_some_posts()->count(); // The total number of posts found matching the current query parameters
    $this->max_num_pages = $this->get_posts_max_pages(); // The total number of pages. Is the result of $found_posts / $posts_per_page
    
    // $this->query_array_dissect(); // fired on QueryPostsModel object creation. this may be for test, or for setting defaults
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
    if(array_key_exists('tax_query', $this->query_vars)) return $this->query_vars['tax_query'];
    return false;
  }
  public function relation_key() {
    $tax_query_array = $this->tax_query_key();
    if(array_key_exists('relation', $tax_query_array)) return $tax_query_array['relation'];
    return false;
  }
  
  public function query_array_dissect2_backup() {

    $posts = $this->get_all_singles();
    $count = $posts->count();

    // set $q data location
    $q = new Json('../public/json/data.min.json');

    // type
    if(array_key_exists('type', $this->query_vars)) {
      $type_plural = type_setting_by_key('single', $this->query_vars['type'], 'items'); // returns 'posts' or 'projects' etc...
      $type_archive = type_setting_by_key('single', $this->query_vars['type'], 'key'); // returns 'blog' or 'portfolio' etc...
      $location = 'site.content_types.'.$type_archive.'.'.$type_plural;

      // set $location based on 'type'. used in from()
      if($this->query_vars['type'] == 'page') $location = 'site.content_types.page';

      $posts = $q
      ->from($location)
      ->get();
    }

    if(array_key_exists('type', $this->query_vars)) {
      if(array_key_exists('tax_query', $this->query_vars)) {
        if(array_key_exists('relation', $this->query_vars['tax_query'])) $relation = $this->query_vars['tax_query']['relation'];

        if($relation == 'OR') {

          // foreach taxonomy. 'OR' relation
          // the OR relation should also be a foreach, but the 2nd taxonomy will add a orWhere query to the main, instead of querying the queried posts...
          // it's working with 'AND' operators...
          // and also seems to be for 'IN' operators

          $main_query = new Json($posts); // set posts
          $new_query = $main_query
          ->where(function($query) {
            $query->where('categories', 'any', 'media')->orWhere('categories', 'any', 'events'); // operator 'IN'
            // $query->where('categories', 'any', 'news')->where('categories', 'any', 'media'); // operator 'AND'
          });

          $new_query2 = $new_query
          ->orWhere(function($query) {
            $query->where('tags', 'any', 'twig')->orWhere('tags', 'any', 'javascript');  // operator 'IN'
            // $query->where('tags', 'any', 'css')->where('tags', 'any', 'twig');  // operator 'AND'
          });

          $posts = $new_query->get();

          //  depending on the relation, i will be doing 2 different types of queries
          // i need to be able to do something specific for the first item in each foreach loop (where/orWhere)

        } else {

          // foreach taxonomy. 'AND' relation. seems to be working
          // queries the set of posts by 1st taxonomy's terms. then queries the queried set by 2nd taxonomy's terms & so on...
          // this all seems to be working...
          // 

          // $taxes_i = 0;
          // foreach($this->query_vars['tax_query'] as $key => $value){
          //   $taxes_iterator = ++$taxes_i;
          //   if(is_array($value)){
          //     if(array_key_exists('taxonomy', $value)) $tax = tax_setting_by_key($type_archive, 'single', $value['taxonomy'], 'key');
          //     if(array_key_exists('terms', $value)) {
          //       if(array_key_exists('operator', $value)) $op = $value['operator'];
          // 
          //       // USE IF ALL ITEMS ARE THE SAME***
          //       // $first_query = new Json($posts);
          //       // $terms = $value['terms'];
          //       // if(is_array($value['terms'])){
          //       //   $posts = $first_query
          //       //   ->where(function($query) use ($terms, $tax, $op) {
          //       //     $first_query_i = 0;
          //       //     foreach($terms as $term){
          //       //       $first_query_iterator = ++$first_query_i;
          //       // 
          //       //       // 'AND' = posts where each have ALL of the listed terms
          //       //       if($op == 'AND'){
          //       //         // $terms_query = $prev->where($tax, 'any', $term);
          //       //         // operator 'AND'
          //       //         // does a 'where' clause for all items
          //       //         $query->where($tax, 'any', $term); 
          //       //       } 
          //       // 
          //       //       // 'IN' = posts where each has at leats ONE of the listed terms
          //       //       if($op == 'IN') {
          //       //         // $terms_query = $prev->orWhere($tax, 'any', $term);
          //       //         // operator 'IN'
          //       //         // for first item, does 'where' clause. subsequent items do a 'orWhere' clause
          //       //         if ($first_query_iterator == 1) {
          //       //           $query->where($tax, 'any', $term);
          //       //         } else {
          //       //           $query->orWhere($tax, 'any', $term);
          //       //         }
          //       //       }
          //       // 
          //       //       // 'NOT IN' = posts where none of the listed terms exist
          //       //       if($op == 'NOT IN') {
          //       //         // $terms_query = $prev->where($tax, 'notany', $term);
          //       //         $query->where($tax, 'notany', $term); 
          //       //       }
          //       // 
          //       //     }
          //       //   })
          //       //   ->get();
          //       // } else {
          //       //   $posts = $first_query
          //       //   ->where(function($query) use ($terms, $tax) {
          //       //     $query->where($tax, 'any', $terms); 
          //       //   })
          //       //   ->get();
          //       // }
          // 
          //       // USE IF NEED 1ST ITEM TO BE DIFFERENT***
          //       if ($taxes_iterator == 1) {
          // 
          //         // first taxonomy
          //         $first_query = new Json($posts);
          //         $terms = $value['terms'];
          // 
          //         if(is_array($value['terms'])){
          //           $posts = $first_query
          //           ->where(function($query) use ($terms, $tax) {
          //             $first_query_i = 0;
          //             foreach($terms as $term){
          //               $first_query_iterator = ++$first_query_i;
          // 
          //               // 'AND' = posts where each have ALL of the listed terms
          //               if($op == 'AND'){
          //                 // $terms_query = $prev->where($tax, 'any', $term);
          //                 // operator 'AND'
          //                 // does a 'where' clause for all items
          //                 $query->where($tax, 'any', $term); 
          //               } 
          // 
          //               // 'IN' = posts where each has at leats ONE of the listed terms
          //               if($op == 'IN') {
          //                 // $terms_query = $prev->orWhere($tax, 'any', $term);
          //                 // operator 'IN'
          //                 // for first item, does 'where' clause. subsequent items do a 'orWhere' clause
          //                 if ($first_query_iterator == 1) {
          //                   $query->where($tax, 'any', $term);
          //                 } else {
          //                   $query->orWhere($tax, 'any', $term);
          //                 }
          //               }
          // 
          //               // 'NOT IN' = posts where none of the listed terms exist
          //               if($op == 'NOT IN') {
          //                 // $terms_query = $prev->where($tax, 'notany', $term);
          //                 $query->where($tax, 'notany', $term); 
          //               }
          // 
          //             }
          //           })
          //           ->get();
          //         } else {
          //           $posts = $first_query
          //           ->where(function($query) use ($terms, $tax) {
          //             $query->where($tax, 'any', $terms); 
          //           })
          //           ->get();
          //         }
          // 
          //       } else {
          // 
          //         // second taxonomy
          //         $next_query = new Json($posts);
          //         $terms = $value['terms'];
          // 
          //         if(is_array($value['terms'])){
          //           $posts = $next_query
          //           ->where(function($query) use ($terms, $tax) {
          //             $next_query_i = 0;
          //             foreach($terms as $term){
          //               $next_query_iterator = ++$next_query_i;
          // 
          //               // 'AND' = posts where each have ALL of the listed terms
          //               if($op == 'AND'){
          //                 // $terms_query = $prev->where($tax, 'any', $term);
          //                 // operator 'AND'
          //                 // does a 'where' clause for all items
          //                 $query->where($tax, 'any', $term); 
          //               } 
          // 
          //               // 'IN' = posts where each has at leats ONE of the listed terms
          //               if($op == 'IN') {
          //                 // $terms_query = $prev->orWhere($tax, 'any', $term);
          //                 // operator 'IN'
          //                 // for first item, does 'where' clause. subsequent items do a 'orWhere' clause
          //                 if ($next_query_iterator == 1) {
          //                   $query->where($tax, 'any', $term);
          //                 } else {
          //                   $query->orWhere($tax, 'any', $term);
          //                 }
          //               }
          // 
          //               // 'NOT IN' = posts where none of the listed terms exist
          //               if($op == 'NOT IN') {
          //                 // $terms_query = $prev->where($tax, 'notany', $term);
          //                 $query->where($tax, 'notany', $term); 
          //               }
          // 
          //             }
          //           })
          //           ->get();
          //         } else {
          //           $posts = $next_query
          //           ->where(function($query) use ($terms, $tax) {
          //             $query->where($tax, 'any', $terms); 
          //           })
          //           ->get();
          //         }
          // 
          //       }
          // 
          //     }
          //   }
          // }

          $taxonomys = array('categories', 'tags');
          $cat_terms = array('media', 'news');
          $tag_terms = array('css', 'javascript');
          
          $taxes_i = 0;
          foreach($taxonomys as $taxx){
            $taxes_iterator = ++$taxes_i;
          
            if ($taxes_iterator == 1) {
          
              // first taxonomy
              $first_query = new Json($posts);
              $posts = $first_query
              ->where(function($query) use ($cat_terms, $taxx) {
                $first_query_i = 0;
                foreach($cat_terms as $term){
                  $first_query_iterator = ++$first_query_i;
          
                  // operator 'IN'
                  // for first item, does 'where' clause. subsequent items do a 'orWhere' clause
                  // if ($first_query_iterator == 1) {
                  //   $query->where($taxx, 'any', $term);
                  // } else {
                  //   $query->orWhere($taxx, 'any', $term);
                  // }
          
                  // operator 'AND'
                  // does a 'where' clause for all items
                  $query->where($taxx, 'any', $term); 
          
                }
              })
              ->get();
          
            } else {
          
              // second taxonomy
              $next_query = new Json($posts);
              $posts = $next_query
              ->where(function($query) use ($tag_terms, $taxx) {
                $next_query_i = 0;
                foreach($tag_terms as $term){
                  $next_query_iterator = ++$next_query_i;
          
                  // operator 'IN'
                  // for first item, does 'where' clause. subsequent items do a 'orWhere' clause
                  // if ($tag_terms_iterator == 1) {
                  //   $query->where($taxx, 'any', $term);
                  // } else {
                  //   $query->orWhere($taxx, 'any', $term);
                  // }
          
                  // operator 'AND'
                  // does a 'where' clause for all items
                  $query->where($taxx, 'any', $term); 
          
                }
              })
              ->get();
          
            }
          
          }

        }
      }
    }

    // $test_query = new Json($posts);
    // $posts = $test_query
    // ->where(function($query) {
    //   $query->where('tags', 'any', 'css')->orWhere('tags', 'any', 'javascript');
    // })
    // ->where(function($query) {
    //   $query->where('categories', 'any', 'news')->orWhere('categories', 'any', 'events');
    // })
    // ->get();

    // if($relation == 'OR') {
    // 
    //   // foreach taxonomy. 'OR' relation
    //   // the OR relation should also be a foreach, but the 2nd taxonomy will add a orWhere query to the main, instead of querying the queried posts...
    //   // it's working with 'AND' operators...
    //   // and also seems to be for 'IN' operators
    // 
    //   $test_query = new Json($posts); // set posts
    //   $new_query = $test_query
    //   ->where(function($query) {
    //     $query->where('categories', 'any', 'media')->orWhere('categories', 'any', 'events'); // operator 'IN'
    //     // $query->where('categories', 'any', 'news')->where('categories', 'any', 'media'); // operator 'AND'
    //   });
    // 
    //   $new_query2 = $new_query
    //   ->orWhere(function($query) {
    //     $query->where('tags', 'any', 'twig')->orWhere('tags', 'any', 'javascript');  // operator 'IN'
    //     // $query->where('tags', 'any', 'css')->where('tags', 'any', 'twig');  // operator 'AND'
    //   });
    // 
    //   $posts = $new_query->get();
    // 
    //   //  depending on the relation, i will be doing 2 different types of queries
    //   // i need to be able to do something specific for the first item in each foreach loop (where/orWhere)
    // 
    // } else {
    // 
    //   // foreach taxonomy. 'AND' relation. seems to be working
    //   // queries the set of posts by 1st taxonomy's terms. then queries the queried set by 2nd taxonomy's terms & so on...
    //   // this all seems to be working...
    // 
    //   $test_query = new Json($posts);
    //   $posts = $test_query
    //   ->where(function($query) {
    //     $query->where('categories', 'any', 'media')->orWhere('categories', 'any', 'events'); // operator 'IN'
    //     // $query->where('categories', 'any', 'news')->where('categories', 'any', 'events'); // operator 'AND'
    //   })
    //   ->get();
    // 
    //   $test_query2 = new Json($posts);
    //   $posts = $test_query2
    //   ->where(function($query) { // 'AND' relation
    //     $query->where('tags', 'any', 'twig')->orWhere('tags', 'any', 'javascript');  // operator 'IN'
    //     // $query->where('tags', 'any', 'css')->where('tags', 'any', 'javascript');  // operator 'AND'
    //   })
    //   ->get();
    // 
    // }

    // $year_query = new Json($posts);
    // $posts = $year_query
    // ->where('date_time', 'year', '2021')
    // ->get();
    // 
    // $month_query = new Json($posts);
    // $posts = $month_query
    // ->where('date_time', 'month', '04')
    // ->get();
    // 
    // $day_query = new Json($posts);
    // $posts = $day_query
    // ->where('date_time', 'day', '04')
    // ->get();

    // TEST***
    // works as expected...
    // if($relation == 'AND') {
    //   $i = 1;
    //   foreach($this->query_vars['tax_query'] as $key => $value){
    //     $iterator = ++$i;
    //     $prev_posts = "prev_posts{$iterator}"; // variable variable with iterator
    //     if(is_array($value)){
    //       $$prev_posts = new Json($posts);
    //       $tax = getTypeTaxSettingBySettingKey(getTypeSettingBySettingKey('single', $this->query_vars['type'], 'key'), 'single', $value['taxonomy'], 'key');
    // 
    //       if(array_key_exists('operator', $value)) $op = $value['operator'];
    // 
    //       if(is_array($value['terms'])){
    //         foreach($value['terms'] as $term) {
    // 
    //           // 'AND' = posts where each have ALL of the listed terms
    //           if($op == 'AND'){
    //             $terms_query = $$prev_posts->where($tax, 'any', $term);
    //           } 
    // 
    //           // 'IN' = posts where each has at leats ONE of the listed terms
    //           if($op == 'IN') {
    //             $terms_query = $$prev_posts->orWhere($tax, 'any', $term);
    //           }
    // 
    //           // 'NOT IN' = posts where none of the listed terms exist
    //           if($op == 'NOT IN') {
    //             $terms_query = $$prev_posts->where($tax, 'notany', $term);
    //           }
    // 
    //         }
    //         $posts = $terms_query->get();
    //       }
    //     }
    //   }
    // }
    // not working
    // if($relation == 'OR') {
    //   // $latest_posts = $posts;
    //   // $prev = new Json($latest_posts);
    //   $i = 1;
    //   foreach($this->query_vars['tax_query'] as $key => $value){
    //     $iterator = ++$i;
    //     $prev_posts = "prev_posts{$iterator}"; // variable variable with iterator
    //     $$prev_posts = new Json($posts);
    //     if(is_array($value)){
    // 
    //       $tax = getTypeTaxSettingBySettingKey(getTypeSettingBySettingKey('single', $this->query_vars['type'], 'key'), 'single', $value['taxonomy'], 'key');
    // 
    //       if(array_key_exists('operator', $value)) $op = $value['operator'];
    // 
    //       if(is_array($value['terms'])){
    //         foreach($value['terms'] as $term) {
    // 
    //           // 'AND' = posts where each have ALL of the listed terms
    //           if($op == 'AND'){
    //             $terms_query = $$prev_posts->where($tax, 'any', $term);
    //           } 
    // 
    //           // 'IN' = posts where each has at leats ONE of the listed terms
    //           if($op == 'IN') {
    //             $terms_query = $$prev_posts->orWhere($tax, 'any', $term);
    //           }
    // 
    //           // 'NOT IN' = posts where none of the listed terms exist
    //           if($op == 'NOT IN') {
    //             $terms_query = $$prev_posts->where($tax, 'notany', $term);
    //           }
    // 
    //         }
    // 
    //       }
    //     }
    //   }
    //   $posts = $terms_query->get();
    // }

    // TEST***
    // $i = 1;
    // foreach($this->query_vars['tax_query'] as $key => $value){
    //   $iterator = ++$i;
    //   $prev_posts = "prev_posts{$iterator}"; // variable variable with iterator
    //   if(is_array($value)){
    //     $$prev_posts = new Json($posts);
    //     $tax = getTypeTaxSettingBySettingKey(getTypeSettingBySettingKey('single', $this->query_vars['type'], 'key'), 'single', $value['taxonomy'], 'key');
    //     if(array_key_exists('operator', $value)) $op = $value['operator'];
    // 
    //     foreach($value['terms'] as $term){
    //       // 'AND' = posts where each have ALL of the listed terms
    //       // if($op == 'AND'){
    //       //   $terms_query = $tax_query->where($tax, 'any', $term);
    //       // } 
    //       // 'IN' = posts where each has at leats ONE of the listed terms
    //       if($op == 'IN') {
    //         $terms_query = $$prev_posts->where($tax, 'any', $term);
    //       }
    //       // 'NOT IN' = posts where none of the listed terms exist
    //       // if($op == 'NOT IN') {
    //       //   $terms_query = $tax_query->where($tax, 'notany', $term);
    //       // }
    //       $posts = $terms_query->get();
    //     }
    //   }
    // }

    echo($posts);
    echo('<hr>');
  }
  
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

    // // date
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
    if($_type && $_tax_query) {
      if($_relation == 'OR') {
    
        /////////////////////////////////////
    
        $posts = new Json($posts);
        $posts = $posts
        ->where(function($query) {
          // $query->where('categories', 'any', 'media')->orWhere('categories', 'any', 'events'); // operator 'IN'
          $query->where('categories', 'any', 'news');
          $query->where('categories', 'any', 'media'); // operator 'AND'
        });
        
        $posts = $posts
        ->orWhere(function($query) {
          // $query->where('tags', 'any', 'twig')->orWhere('tags', 'any', 'javascript');  // operator 'IN'
          $query->where('tags', 'any', 'css');
          $query->where('tags', 'any', 'javascript');  // operator 'AND'
        });
    
        /////////////////////////////////////
    
        // $taxes_i = 0;
        // foreach($_tax_query as $key => $value){
        // 
        //   if (is_array($value)) {
        //     $taxes_iterator = ++$taxes_i;
        //     if(array_key_exists('taxonomy', $value)) $tax = tax_setting_by_key($type_archive, 'single', $value['taxonomy'], 'key');
        //     if(array_key_exists('terms', $value)) {
        //       if(array_key_exists('operator', $value)) $op = $value['operator'];  
        // 
        //       $terms = $value['terms'];
        // 
        //       if ($taxes_iterator == 1) {
        // 
        //         $posts = $posts
        //         ->where(function($query) use ($terms, $tax, $op) {
        // 
        //           $first_query_i = 0;
        //           foreach($terms as $term){
        //             $first_query_iterator = ++$first_query_i;
        // 
        //             // 'AND' = posts where each have ALL of the listed terms
        //             if($op == 'AND'){
        //               // $terms_query = $prev->where($tax, 'any', $term);
        //               // operator 'AND'
        //               // does a 'where' clause for all items
        //               $query->where($tax, 'any', $term); 
        //             } 
        // 
        //             // 'IN' = posts where each has at leats ONE of the listed terms
        //             if($op == 'IN') {
        //               // $terms_query = $prev->orWhere($tax, 'any', $term);
        //               // operator 'IN'
        //               // for first item, does 'where' clause. subsequent items do a 'orWhere' clause
        //               if ($first_query_iterator == 1) {
        //                 $query->where($tax, 'any', $term);
        //               } else {
        //                 $query->orWhere($tax, 'any', $term);
        //               }
        //             }
        // 
        //             // 'NOT IN' = posts where none of the listed terms exist
        //             if($op == 'NOT IN') {
        //               // $terms_query = $prev->where($tax, 'notany', $term);
        //               $query->where($tax, 'notany', $term); 
        //             }
        // 
        //           }
        // 
        //           // // $query->where('categories', 'any', 'media')->orWhere('categories', 'any', 'events'); // operator 'IN'
        //           // $query->where('categories', 'any', 'news')->where('categories', 'any', 'media'); // operator 'AND'
        //         });
        // 
        //       } else {
        // 
        //         $new_posts = $posts;
        //         $posts = $new_posts
        //         ->orWhere(function($query) use ($terms, $tax, $op) {
        // 
        //           $next_query_i = 0;
        //           foreach($terms as $term){
        //             $next_query_iterator = ++$next_query_i;
        // 
        //             // 'AND' = posts where each have ALL of the listed terms
        //             if($op == 'AND'){
        //               // $terms_query = $prev->where($tax, 'any', $term);
        //               // operator 'AND'
        //               // does a 'where' clause for all items
        //               $query->where($tax, 'any', $term); 
        //             } 
        // 
        //             // 'IN' = posts where each has at leats ONE of the listed terms
        //             if($op == 'IN') {
        //               // $terms_query = $prev->orWhere($tax, 'any', $term);
        //               // operator 'IN'
        //               // for first item, does 'where' clause. subsequent items do a 'orWhere' clause
        //               if ($next_query_iterator == 1) {
        //                 $query->where($tax, 'any', $term);
        //               } else {
        //                 $query->orWhere($tax, 'any', $term);
        //               }
        //             }
        // 
        //             // 'NOT IN' = posts where none of the listed terms exist
        //             if($op == 'NOT IN') {
        //               // $terms_query = $prev->where($tax, 'notany', $term);
        //               $query->where($tax, 'notany', $term); 
        //             }
        // 
        //           }
        // 
        //           // $query->where('tags', 'any', 'twig')->orWhere('tags', 'any', 'javascript');  // operator 'IN'
        //           // $query->where('tags', 'any', 'css');
        //           // $query->where('tags', 'any', 'javascript');  // operator 'AND'
        //         });
        // 
        //       }
        //     }
        //   }
        // }
    
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
    
    // return $posts;
    return $posts->get();
  }
  
  // statics
  private function static_args_string(){
    $args = 'type=post&categories=news,media&tags=twig,css&s=lorem&year=2021&month=1&day=1&per_page=5&p=2';
    return $args;
  }
  private function static_args_array(){
    $args = array(
      'type' => 'post', // content type. else all types. may try to use singular labels like 'post' instead of 'blog' here
      'tax_query' => array( // taxonomy query
        'relation' => 'AND', // and works!
        array(
          'taxonomy' => 'category', // taxonomy to query
          'terms' => array('news', 'media'), // terms to query posts by. use mutiple for posts in multiple tax terms
          'operator' => 'AND' // 'AND', 'IN', 'NOT IN'. 'IN' is default. AND means posts must have all terms in the array. In means just one.
        ),
        array(
          'taxonomy' => 'tag', // query by another tax. rem relation will define either one taxonomy or other or both etc...
          'terms' => array('css', 'javascript'), // terms to query posts by. use mutiple for posts in multiple tax terms
          'operator' => 'AND'
        ),
      ),
      's' => 'blah', // search in posts' title/excerpt with this
      'date' => array( // query posts by date. will need to format dates in json file. or just use Year as a string. 
        array(
          'year'  => 2021,
          'month' => 1,
          'day'   => 1,
        ),
      ),
      'name' => 'lorem-ipsum-dolor', // by post name (archived types like blog/portfolio)
      'pagename' => 'contact', // by page name (pages only)
      'per_page' => 5, // posts per page to return
      'p' => 2, // paged page of posts to return
      'show_all' => true// else show all posts, disables pagination & negates per_page & p
    );
    return $args;
  }
  
  // modifying/getting some posts
  public function get_some_posts() {
    // if $args is null, get all posts
    // if(!$this->args) $the_posts = $this->get_all_singles();
    $the_posts = new Json; // blank
    return $the_posts;
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

// if($relation == 'OR') {
// 
//   // foreach taxonomy. 'OR' relation
//   // the OR relation should also be a foreach, but the 2nd taxonomy will add a orWhere query to the main, instead of querying the queried posts...
//   // it's working with 'AND' operators...
//   // and also seems to be for 'IN' operators
// 
//   $test_query = new Json($posts); // set posts
//   $new_query = $test_query
//   ->where(function($query) {
//     $query->where('categories', 'any', 'media')->orWhere('categories', 'any', 'events'); // operator 'IN'
//     // $query->where('categories', 'any', 'news')->where('categories', 'any', 'media'); // operator 'AND'
//   });
// 
//   $new_query2 = $new_query
//   ->orWhere(function($query) {
//     $query->where('tags', 'any', 'twig')->orWhere('tags', 'any', 'javascript');  // operator 'IN'
//     // $query->where('tags', 'any', 'css')->where('tags', 'any', 'twig');  // operator 'AND'
//   });
// 
//   $posts = $new_query->get();
// 
//   //  depending on the relation, i will be doing 2 different types of queries
//   // i need to be able to do something specific for the first item in each foreach loop (where/orWhere)
// 
// } else {
// 
//   // foreach taxonomy. 'AND' relation. seems to be working
//   // queries the set of posts by 1st taxonomy's terms. then queries the queried set by 2nd taxonomy's terms & so on...
//   // this all seems to be working...
// 
//   $test_query = new Json($posts);
//   $posts = $test_query
//   ->where(function($query) {
//     $query->where('categories', 'any', 'media')->orWhere('categories', 'any', 'events'); // operator 'IN'
//     // $query->where('categories', 'any', 'news')->where('categories', 'any', 'events'); // operator 'AND'
//   })
//   ->get();
// 
//   $test_query2 = new Json($posts);
//   $posts = $test_query2
//   ->where(function($query) { // 'AND' relation
//     $query->where('tags', 'any', 'twig')->orWhere('tags', 'any', 'javascript');  // operator 'IN'
//     // $query->where('tags', 'any', 'css')->where('tags', 'any', 'javascript');  // operator 'AND'
//   })
//   ->get();
// 
// }

/*
*
* usage....
* no need to json query the posts as the posts is just a simple array
* can basically filter the posts based on the args using simple php
* see helpers.php. basically a bunch of helpers can do to filter the array according to the conditions
*
* what are the filtering conditions?
*
* date: the date of objects like year, month etc
*
* taxonomy term: i should be able to narrow a set of results based on a given taxonomy term
* example: /blog?cat=news&tag=twig. this would show posts that are both in 'news' & 'twig'
* a good way to go would be to allow for mutiple values for a given filter like category or tag
* example: /blog?cat=news,media&tag=twig. this would show all posts from 'news' & 'media' that are also in 'twig'
*
* only taxonomy query params that are part of the given content type should work
* best way for this may be to check against the existing posts, whether taxonomies exist in their data
* if a param is present for a taxonomy that exists within the existing posts data, we should use it to modify the posts
*
* UPDATE. i probs need to avoid modifying existing posts as they are already paginated. I need to get whole new sets of posts.
* I will need to take the existing query and convert any page paramters like /blog/ into a type paramter & add that to the query
* it is now necessary to use the Json class
* other archives where this functionality is to work: term archives
* /blog/categories/news?tag=twig&date=2021
* in this case, the 'blog' & 'categories' page params would act like query params. they will be fed into the query
* i would need to deconstruct the url part of the uri string & add that to the args. and remove posts
* no need for this functionality to work with taxonomy archives
*
*/

// TESTING***
// $this->hello = $this->get_hello_arg(); // TESTING***. getting a value from a $this->args key & setting it as a class property
// public function get_hello_arg() {
//   $data = null;
//   if(isset($this->args['hello'])) {
//     $data = $this->args['hello'];
//   };
//   return $data;
// }
// $posts is just a fallback for now. tehse are the original posts for the archive before modifying the query
// public function get_some_posts_hello() {
//   // TESTING***. if hello is present, print the value....
//   if($this->hello) print_r($this->hello.'<hr>');
// 
//   return $this->default_posts; // return the posts that were given for now. posts will be removed as a class requisite later
// }
// look for the relevant keys in the args array & set that data to some variables
// for each variable item, when it exists, do the query. some will be more complicated than others (tax_query)
// once this is returning posts, set about dissecting the args string into an array, with only the relevant keys/values.

// public function query_array_dissect() {
// 
//   /*
//   *
//   * 1. Set these defaults. basically if no type arguments, posts should be from all singulars
//   *
//   */
// 
//   $posts = $this->get_all_singles();
//   $count = $posts->count();
// 
//   /*
//   *
//   * 2. Check when certain parameters exists in the query args array, & doing stuff in those circumstances
//   *
//   */
// 
//   // set $q data location
//   $q = new Json('../public/json/data.min.json');
// 
//   if(array_key_exists('type', $this->query_vars)) {
// 
//     // set $location based on 'type'. used in from()
//     if($this->query_vars['type'] == 'page'){
//       $location = 'site.content_types.page';
//     } else {
//       $type_plural = getTypeSettingBySettingKey('single', $this->query_vars['type'], 'items'); // returns 'posts' or 'projects' etc...
//       $type_archive = getTypeSettingBySettingKey('single', $this->query_vars['type'], 'key'); // returns 'blog' or 'portfolio' etc...
//       $location = 'site.content_types.'.$type_archive.'.'.$type_plural;
//     }
// 
//     $posts = $q
//     ->from($location)
//     // ->where('type', '=', 'post')
//     ->get();
// 
//     if(array_key_exists('tax_query', $this->query_vars)) {
//       foreach($this->query_vars['tax_query'] as $key => $value){
//         if(is_array($value)){
// 
//           if(array_key_exists('taxonomy', $value)) {
//             $tax = getTypeTaxSettingBySettingKey(getTypeSettingBySettingKey('single', $this->query_vars['type'], 'key'), 'single', $value['taxonomy'], 'key');
//           }
// 
//           if(array_key_exists('terms', $value)) {
// 
//             if(is_array($value['terms'])){
// 
//               foreach($value['terms'] as $term){
// 
//               }
// 
//             } else {
//               // or just get the posts from the single term
//               $posts = $$q->where($tax, 'any', $value['terms'])->get();
//               $count = $posts->count();
//             };
// 
//           };
//         }
//       }
//     }
//   }
// 
//   $search_query = new Json($posts);
//   $posts = $search_query
//   ->where('excerpt', 'match', '(?i)'.'lorem')
//   ->orWhere('title', 'match', '(?i)'.'lorem')
//   ->get();
// 
//   if(array_key_exists('relation', $this->query_vars['tax_query'])) {
//     $relation = $this->query_vars['tax_query']['relation'];
//   };
// 
//   $latest_posts = $posts;
//   $prev = new Json($latest_posts);
//   if($relation == 'OR') {
//     foreach($this->query_vars['tax_query'] as $key => $value){
//       if(is_array($value)){
// 
//         $tax = getTypeTaxSettingBySettingKey(getTypeSettingBySettingKey('single', $this->query_vars['type'], 'key'), 'single', $value['taxonomy'], 'key');
// 
//         if(array_key_exists('operator', $value)) $op = $value['operator'];
// 
//         if(is_array($value['terms'])){
//           foreach($value['terms'] as $term) {
//             $prev = new Json($latest_posts);
// 
//             // 'AND' = posts where each have ALL of the listed terms
//             if($op == 'AND'){
//               $terms_query = $prev->where($tax, 'any', $term);
//             } 
// 
//             // 'IN' = posts where each has at leats ONE of the listed terms
//             if($op == 'IN') {
//               $terms_query = $prev->orWhere($tax, 'any', $term);
//             }
// 
//             // 'NOT IN' = posts where none of the listed terms exist
//             if($op == 'NOT IN') {
//               $terms_query = $prev->where($tax, 'notany', $term);
//             }
// 
//           }
// 
//         }
//       }
//     }
//     $posts = $terms_query->get();
//   } else {
//     $i = 1;
//     foreach($this->query_vars['tax_query'] as $key => $value){
//       $iterator = ++$i;
//       $prev_posts = "prev_posts{$iterator}"; // variable variable with iterator
//       if(is_array($value)){
//         $$prev_posts = new Json($posts);
//         $tax = getTypeTaxSettingBySettingKey(getTypeSettingBySettingKey('single', $this->query_vars['type'], 'key'), 'single', $value['taxonomy'], 'key');
// 
//         if(array_key_exists('operator', $value)) $op = $value['operator'];
// 
//         if(is_array($value['terms'])){
//           foreach($value['terms'] as $term) {
// 
//             // 'AND' = posts where each have ALL of the listed terms
//             if($op == 'AND'){
//               $terms_query = $$prev_posts->where($tax, 'any', $term);
//             } 
// 
//             // 'IN' = posts where each has at leats ONE of the listed terms
//             if($op == 'IN') {
//               $terms_query = $$prev_posts->orWhere($tax, 'any', $term);
//             }
// 
//             // 'NOT IN' = posts where none of the listed terms exist
//             if($op == 'NOT IN') {
//               $terms_query = $$prev_posts->where($tax, 'notany', $term);
//             }
// 
//           }
//           $posts = $terms_query->get();
//         }
//       }
//     }
//   }
// 
//   // $i = 1;
//   // foreach($this->query_vars['tax_query'] as $key => $value){
//   //   $iterator = ++$i;
//   //   $prev_posts = "prev_posts{$iterator}"; // variable variable with iterator
//   //   if(is_array($value)){
//   //     $$prev_posts = new Json($posts);
//   //     $tax = getTypeTaxSettingBySettingKey(getTypeSettingBySettingKey('single', $this->query_vars['type'], 'key'), 'single', $value['taxonomy'], 'key');
//   // 
//   //     if(array_key_exists('operator', $value)) {
//   //       $op = $value['operator'];
//   //     }
//   // 
//   //     if(is_array($value['terms'])){
//   //       foreach($value['terms'] as $term) {
//   // 
//   //         // 'AND' = posts where each have ALL of the listed terms
//   //         if($op == 'AND'){
//   //           $terms_query = $$prev_posts->where($tax, 'any', $term);
//   //         } 
//   // 
//   //         // 'IN' = posts where each has at leats ONE of the listed terms
//   //         if($op == 'IN') {
//   //           $terms_query = $$prev_posts->orWhere($tax, 'any', $term);
//   //         }
//   // 
//   //         // 'NOT IN' = posts where none of the listed terms exist
//   //         if($op == 'NOT IN') {
//   //           $terms_query = $$prev_posts->where($tax, 'notany', $term);
//   //         }
//   // 
//   //       }
//   //       $posts = $terms_query->get();
//   //     }
//   //   }
//   // }
// 
// 
//   // $cat_query = new Json($posts);
//   // $posts = $cat_query
//   // ->where('categories', 'any', 'news')
//   // ->where('categories', 'any', 'media')
//   // ->get();
//   // 
//   // $tag_query = new Json($posts);
//   // $posts = $tag_query
//   // ->where('tags', 'any', 'javacsript')
//   // ->orWhere('tags', 'any', 'twig')
//   // ->get();
//   // 
//   // $year_query = new Json($posts);
//   // $posts = $year_query
//   // ->where('date_time', 'year', '2021')
//   // ->get();
//   // 
//   // $month_query = new Json($posts);
//   // $posts = $month_query
//   // ->where('date_time', 'month', '04')
//   // ->get();
//   // 
//   // $day_query = new Json($posts);
//   // $posts = $day_query
//   // ->where('date_time', 'day', '04')
//   // ->get();
// 
//   // echo($posts);
// 
//   // if 'type' exists, get posts based on the 'type'
//   // if(array_key_exists('type', $this->query_vars)) {
//   //   $posts = $this->process_type();
//   //   $count = $posts->count();
//   //   // if 'tax_query' exists, get posts based on the 'tax_query'. but only if 'type' exists first
//   //   if(array_key_exists('tax_query', $this->query_vars)) {
//   //     $posts = $this->process_tax_query($posts);
//   //     $count = $posts->count();
//   //   };
//   // };
//   // 
//   // // if 's'
//   // if(array_key_exists('s', $this->query_vars)) {
//   //   print_r($this->query_vars['s']); // the search string
//   //   echo('<hr>');
//   // };
//   // 
//   // // if 'date' (post date)
//   // if(array_key_exists('date', $this->query_vars)) {
//   //   if (is_array($this->query_vars['date'])) {
//   //     foreach($this->query_vars['date'] as $date_array){
//   //       foreach($date_array as $key => $value){
//   //         print_r($value);
//   //         echo('<hr>');
//   //       }
//   //     }
//   //   }
//   // };
//   // 
//   // // if 'name' (post slug)
//   // if(array_key_exists('name', $this->query_vars)) {
//   //   print_r($this->query_vars['name']); // the slug
//   //   echo('<hr>');
//   // };
//   // 
//   // // if 'pagename' (page slug)
//   // if(array_key_exists('pagename', $this->query_vars)) {
//   //   print_r($this->query_vars['pagename']); // the slug
//   //   echo('<hr>');
//   // };
//   // 
//   // // if 'per_page' (posts per page setting)
//   // if(array_key_exists('per_page', $this->query_vars)) {
//   //   print_r($this->query_vars['per_page']); // the per page setting
//   //   echo('<hr>');
//   // };
//   // 
//   // // if 'p' (paged page to display)
//   // if(array_key_exists('p', $this->query_vars)) {
//   //   print_r($this->query_vars['p']); // the paged page integer
//   //   echo('<hr>');
//   // };
//   // 
//   // // if 'show_all' exists (ignore pagination & show all posts)
//   // if(array_key_exists('show_all', $this->query_vars)) {
//   //   if($this->query_vars['show_all']) { // if 'show_all' is true
//   //     print_r('show_all = true');
//   //     echo('<hr>');
//   //   };
//   // };
// 
//   echo($posts);
//   echo('<hr>');
//   // return $posts;
// 
// }
// public function process_type() {
// 
//   /*
//   *
//   * 1. If 'type' = 'page', set a page-specific data location. Else set the data location/s based on the archived content-types (config.types)
//   *
//   */
// 
//   if($this->query_vars['type'] == 'page'){
//     $location = 'site.content_types.page';
//   } else {
//     $type_plural = getTypeSettingBySettingKey('single', $this->query_vars['type'], 'items'); // returns 'posts' or 'projects' etc...
//     $type_archive = getTypeSettingBySettingKey('single', $this->query_vars['type'], 'key'); // returns 'blog' or 'portfolio' etc...
//     $location = 'site.content_types.'.$type_archive.'.'.$type_plural;
//   }
// 
//   /*
//   *
//   * 2. Get & set $posts based on the given type
//   *
//   */
// 
//   $q = new Json('../public/json/data.min.json');
//   $posts = $q->from($location)->get();
// 
//   return $posts;
// }
// public function process_tax_query($posts) {
// 
//   /*
//   *
//   * 1. If 'relation' exists in 'tax_query', set the $relation variable
//   *
//   */
// 
//   if(array_key_exists('relation', $this->query_vars['tax_query'])) {
//     $relation = $this->query_vars['tax_query']['relation'];
//   };
// 
//   /*
//   *
//   * 2. Depending on the relation setting, we will combine whats in the tax_query using AND/OR
//   *
//   */
// 
//   if($relation == 'OR') {
// 
//     // test
// 
//     // set $q data location
//     $q = new Json('../public/json/data.min.json');
// 
//     // set $location based on 'type'. used in from()
//     if($this->query_vars['type'] == 'page'){
//       $location = 'site.content_types.page';
//     } else {
//       $type_plural = getTypeSettingBySettingKey('single', $this->query_vars['type'], 'items'); // returns 'posts' or 'projects' etc...
//       $type_archive = getTypeSettingBySettingKey('single', $this->query_vars['type'], 'key'); // returns 'blog' or 'portfolio' etc...
//       $location = 'site.content_types.'.$type_archive.'.'.$type_plural;
//     }
// 
//     $posts = $q
//     ->from($location)
//     ->where('type', '=', 'post');
// 
//     $search_query = new Json($posts);
//     $posts = $search_query
//     ->where('excerpt', 'match', '(?i)'.'lorem')
//     ->orWhere('title', 'match', '(?i)'.'lorem');
// 
//     $cat_query = new Json($posts);
//     $posts = $cat_query
//     ->where('categories', 'any', 'news')
//     ->where('categories', 'any', 'media');
// 
//     $tag_query = new Json($posts);
//     $posts = $tag_query
//     ->where('tags', 'any', 'javacsript')
//     ->orWhere('tags', 'any', 'twig');
// 
//     $year_query = new Json($posts);
//     $posts = $year_query
//     ->where('date_time', 'year', '2021');
// 
//     $month_query = new Json($posts);
//     $posts = $month_query
//     ->where('date_time', 'month', '04');
// 
//     $day_query = new Json($posts);
//     $posts = $day_query
//     ->where('date_time', 'day', '04');
// 
//     echo($posts->get());
// 
//   } else {
//     /*
//     *
//     * For each item in the 'tax_query' array...
//     *
//     */
//     $i = 1; // need to iterate
//     foreach($this->query_vars['tax_query'] as $key => $value){
//       $iterator = ++$i;
//       $q = "q{$iterator}"; // variable variable with iterator
// 
//       // for each item in 'tax_query' array, make sure we are working on array first
//       // the only parameter under 'tax_query' that isnt an array with taxonomy data, should be 'relation'
//       if(is_array($value)){
// 
//         // if 'tax_query' array contains key 'taxonomy', set the locations. 'taxonomy' is required
//         if(array_key_exists('taxonomy', $value)) {
//           $tax = getTypeTaxSettingBySettingKey(getTypeSettingBySettingKey('single', $this->query_vars['type'], 'key'), 'single', $value['taxonomy'], 'key');
//         };
// 
//         // if 'tax_query' array contains key 'terms'. 'terms' is required but can be single value or an array
//         if(array_key_exists('terms', $value)) {
//           $$q = new Json($posts);
//           // if 'terms' is an array...
//           if(is_array($value['terms'])){
//             // loop thru & set new query variable
//             foreach($value['terms'] as $term){
//               $terms_query = $$q->where($tax, 'any', $term);
//             }
//             // get the posts from the loop terms
//             $posts = $terms_query->get();
//             $count = $posts->count();
//           } else {
//             // or just get the posts from the single term
//             $posts = $$q->where($tax, 'any', $value['terms'])->get();
//             $count = $posts->count();
//           };
//         };
// 
//       }
// 
//     }
//   };
// 
//   return $posts;
// }