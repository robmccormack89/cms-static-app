<?php
namespace Rmcc;

class QueryModel {
  
  /*
  *
  * Examples of inputs into this class
  *
  */
  protected static $_argsString = 'type=post&categories=news,media&tags=css,javascript&s=lorem&year=2021&month=4&day=4&name=lorem&per_page=3&p=1&show_all';
  protected static $_argsArray = array(
    // working. takes string & content type singular label (post, project etc...)
    'type' => 'post',
    
    // working. takes array with relation key & sub-arrays
    'tax_query' => array(
      // relation is required for now as there are checks based the iteration of the first array in this sequence, which should be 2nd when relation exists
      'relation' => 'AND', // working: 'AND', 'OR'. Deaults to 'AND'.
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
    'show_all' => true
  );
      
  /*
  *
  * Properties based on https://developer.wordpress.org/reference/classes/wp_query/#properties
  *
  */
  public function __construct(array $args) {
    $this->args = $args;
    $this->query = $this->getString(); // Holds the query string that was passed to the query object
    $this->query_vars = $this->getArray(); // An associative array containing the dissected $query: an array of the query variables and their respective values.
    $this->queried_object = $this->getQueryQueriedObject(); // Can hold information on the requested category, author, post or Page, archive etc.,.
    $this->posts = $this->getPosts(); // Gets filled with the requested posts
    $this->post_count = $this->getPostsPerPage(); // The number of posts being displayed. per page
    $this->found_posts = $this->getPostsCount(); // The total number of posts found matching the current query parameters
    $this->max_num_pages = $this->getPostsMaxPages(); // The total number of pages. Is the result of $found_posts / $posts_per_page
    // $this->init();
  }
  public function init() {
    echo($this->queried_object.': the queried object');
    echo('<hr>');
    echo($this->found_posts.': posts found');
    echo('<hr>');
    echo($this->post_count.': per_page setting');
    echo('<hr>');
    echo($this->pagedKey().': page we should be on');
    echo('<hr>');
    if($this->isPaged()){
      print_r('we are paged!!');
      echo('<hr>');
    }
    print_r($this->paramsToArgs());
    echo('<hr>');
    print_r(self::$_argsArray);
    echo('<hr>');
    echo($this->max_num_pages);
    echo('<hr>');
  }
  
  /*
  *
  * Set $this->query & $this->query_vars
  *
  */
  private function getString() {
    $data = '';
    if(is_string($this->args)) {
      $data = $this->args;
    }
    return $data;
  }
  private function getArray() {
    
    if(!empty($this->getString())){
      $data = $this->paramsToArgs();
    }
    
    if(is_array($this->args)) {
      $data = $this->args;
    }
    
    return $data;
  }
  
  /*
  *
  * Archive Meta Stuff. Getting the archive meta data
  *
  */
  private function getQueryQueriedObject() {
    
    if(!$this->typeKey()) $data = (new QueriedObjectModel())->getQueriedObject();
    
    if($this->typeKey()) $data = (new QueriedObjectModel(type_setting_by_key('single', $this->typeKey(), 'key')))->getQueriedObject();
    
    // $_tax_query = $this->taxQueryKey();
    // if($_tax_query) {
    //   $data = (new QueriedObjectModel($type, $tax, $term))->getQueriedObject();
    // }
    
    return $data;
  }
  
  /*
  *
  * String Params dissection
  *
  */
  private function paramsDissect() {
    $thestring = $this->query;
    $string_array = parse_str($thestring, $output);
    return $output;
  }
  private function paramsToArgs() {
    
    $new_args_array = array();
    
    if($this->typeParam()){
      $new_args_array['type'] = $this->typeParam();
    }
    
    if($this->typeParam()){
      if($this->taxParams()){
        $new_args_array['tax_query']['relation'] = 'AND';
        foreach($this->taxParams() as $tax => $value){
          $type = type_setting_by_key('single', $this->typeParam(), 'key');
          $new_args_array['tax_query'][] = array(
            'taxonomy' => tax_setting_by_key($type, 'key', $tax, 'single'),
            'terms' => explode(',', $value),
            'operator' => 'AND'
          );
        }
      }
    }
    
    if($this->searchParam()){
      $new_args_array['s'] = $this->searchParam();
    }
    
    if($this->yearParam() || $this->monthParam() || $this->dayParam()){
      if($this->yearParam()){
        $new_args_array['date']['year'] = $this->yearParam();
      }
      if($this->monthParam()){
        $new_args_array['date']['month'] = $this->monthParam();
      }
      if($this->dayParam()){
        $new_args_array['date']['day'] = $this->dayParam();
      }
      if($this->yearParam() {
        $new_args_array['date'][] = array(
          'year' => $this->yearParam()
        );
      }
    }
    
    if($this->nameParam()){
      $new_args_array['name'] = $this->nameParam();
    }
    
    if($this->perPageParam()){
      $new_args_array['per_page'] = $this->perPageParam();
    }
    
    if($this->pagedParam()){
      $new_args_array['p'] = $this->pagedParam();
    }
    
    if($this->showAllParam()){
      $new_args_array['show_all'] = true;
    }
    
    return $new_args_array;
  }
  
  /*
  *
  * Posts Stuff
  *
  */
  private function getPostsQuery() {
    
    $q = new Json('../public/json/data.min.json');
    
    // dynamic content types (ALL)
    $content_types = $q->find('site.content_types')->toArray();
    $results = array();
    foreach($content_types as $key => $value) {
      if($key == 'page'){
        $array = $q->copy()->reset()->find('site.content_types.'.$key)->toArray();
      } else {
        $items = type_setting_by_key('key', $key, 'items');
        $array = $q->copy()->reset()->find('site.content_types.'.$key.'.'.$items)->toArray();
      }
      $results[] = $array;
    }
    $posts = (new Json())->collect(array_merge(...$results));
    $count = $posts->count();
    
    /*
    *
    * 3. GETTING SOME KEY VALUES & SETTING THEM AS VARIABLES TO BE USED BELOW
    *
    */
    $_type = $this->typeKey();
    $_search = $this->searchKey();
    $_date = $this->dateKey();
    $_tax_query = $this->taxQueryKey();
    $_relation = $this->relationKey();
    $_name = $this->nameKey();
    $_per_page = $this->getPostsPerPage();
    
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
    
      $posts = $q->copy()->reset()->from($_location);
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
    
      $_year = $this->yearKey();
      $_month = $this->monthKey();
      $_day = $this->dayKey();
    
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
    * 10. IF isPaged, TAKE THE REMAINING POSTS & CHUNK THEM BASED ON PAGED SETTINGS, ELSE get() THE REMAINING POSTS AS THEY ARE
    *
    */
    if($this->isPaged()){
      $posts = new Json($posts);
      $paged_posts = $posts->chunk($_per_page);
      $posts = $this->getPagedPosts($paged_posts);
    } else {
      $posts = $posts->get();
    }
    
    /*
    *
    * 11. SET THE POST TEASES DATA
    *
    */
    $posts = $this->setPostsTeaseData($posts);
    
    /*
    *
    * 12. RETURN $data & $count
    *
    */
    return array('posts' => $posts, 'count' => $_count);
  }
  private function getPostsCount() {
    $data = $this->getPostsQuery();
    return $data['count'];
  }
  public function getPosts() {
    $data = $this->getPostsQuery();
    return $data['posts'];
  }
  
  /*
  *
  * Posts Tease Stuff
  *
  */
  private function setPostsTeaseData($posts) {
    $linked_posts = $this->setPostTeaseLinkData($posts);
    $termed_posts = $this->setPostTeaseTermData($linked_posts);
    return $termed_posts;
  }
  private function setPostTeaseLinkData($posts) {
    $data = null;
    if($posts){
      foreach ($posts as $post) {
        if(isset($post['type']) && $post['type'] == 'page'){
          $post['link'] = '/'.$post['slug'];
        } else {
          $post['link'] = '/'.type_setting_by_key('single', $post['type'], 'key').'/'.type_setting_by_key('single', $post['type'], 'items').'/'.$post['slug'];
        }
        $data[] = $post;
      }
    }
    return $data;
  }
  private function setPostTeaseTermData($posts) {
    $data = null;
    if($posts){
      foreach ($posts as $post) {
        if($post['type'] !== 'page') {
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
        } else {
          $post = $post;
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
  private function isPaged() {
    if(!$this->showAllKey()) return true;
  }
  private function getPagedPosts($posts) {
    $data = false;
    
    $p = $this->pagedKey();
  
    $offset = $p ? $p - 1 : 0;
    
    if (!isset($posts[$offset])) $posts[$offset] = null;
  
    $data = $posts[$offset];
    
    return $data;
  }
  
  /*
  *
  * Various string params to check for. If string, string -> array
  *
  */
  private function typeParam() {
    $string_args = $this->paramsDissect();
    if(array_key_exists('type', $string_args)) return $string_args['type'];
    return false;
  }
  private function taxParams() {
    $string_args = $this->paramsDissect();
    $taxes = array();
    foreach($GLOBALS['config']['types'] as $type){
      if(array_key_exists('taxonomies', $type)) {
        foreach($type['taxonomies'] as $tax => $value){
          $taxes[$tax] = $tax;
        }
      }
    }
    $result = array_intersect_key($string_args, $taxes);
    if($result) return $result;
    return false;
  }
  private function searchParam() {
    $string_args = $this->paramsDissect();
    if(array_key_exists('s', $string_args)) return $string_args['s'];
    return false;
  }
  private function nameParam() {
    $string_args = $this->paramsDissect();
    if(array_key_exists('name', $string_args)) return $string_args['name'];
    return false;
  }
  private function yearParam() {
    $string_args = $this->paramsDissect();
    if(array_key_exists('year', $string_args)) return $string_args['year'];
    return false;
  }
  private function monthParam() {
    $string_args = $this->paramsDissect();
    if(array_key_exists('month', $string_args)) return $string_args['month'];
    return false;
  }
  private function dayParam() {
    $string_args = $this->paramsDissect();
    if(array_key_exists('day', $string_args)) return $string_args['day'];
    return false;
  }
  private function perPageParam() {
    $string_args = $this->paramsDissect();
    if(array_key_exists('per_page', $string_args)) return $string_args['per_page'];
    return false;
  }
  private function pagedParam() {
    $string_args = $this->paramsDissect();
    if(array_key_exists('p', $string_args)) return $string_args['p'];
    return false;
  }
  private function showAllParam() {
    $string_args = $this->paramsDissect();
    if(array_key_exists('show_all', $string_args)) return true;
    return false;
  }
  
  /*
  *
  * Various keys to check for. If array
  *
  */
  private function typeKey() {
    if($this->query_vars && array_key_exists('type', $this->query_vars)) return $this->query_vars['type'];
    return false;
  }
  private function taxQueryKey() {
    if($this->query_vars && array_key_exists('tax_query', $this->query_vars) && $this->typeKey()) return $this->query_vars['tax_query'];
    return false;
  }
  private function relationKey() {
    $tax_query_array = $this->taxQueryKey();
    if($tax_query_array){
      if(array_key_exists('relation', $tax_query_array)) {
        return $tax_query_array['relation'];
      }
    }
    return false;
  }
  private function searchKey() {
    if($this->query_vars && array_key_exists('s', $this->query_vars)) return $this->query_vars['s'];
    return false;
  }
  private function nameKey() {
    if($this->query_vars && array_key_exists('name', $this->query_vars)) return $this->query_vars['name'];
    return false;
  }
  private function dateKey() {
    if($this->query_vars && array_key_exists('date', $this->query_vars)) return $this->query_vars['date'];
    return false;
  }
  private function yearKey() {
    $date = $this->dateKey();
    if(array_key_exists('year', $date)) return $date['year'];
    return false;
  }
  private function monthKey() {
    $date = $this->dateKey();
    if(array_key_exists('month', $date)) return $date['month'];
    return false;
  }
  private function dayKey() {
    $date = $this->dateKey();
    if(array_key_exists('day', $date)) return $date['day'];
    return false;
  }
  private function perPageKey() {
    if($this->query_vars && array_key_exists('per_page', $this->query_vars)) return $this->query_vars['per_page'];
    return false;
  }
  private function pagedKey() {
    if($this->query_vars && array_key_exists('p', $this->query_vars)) return $this->query_vars['p'];
    return false;
  }
  private function showAllKey() {
    if($this->query_vars && array_key_exists('show_all', $this->query_vars)) return $this->query_vars['show_all'];
    return false;
  }
  
  /*
  *
  * Properties Configuration
  *
  */
  private function getPostsPerPage() {
    $per_page = $this->perPageKey() ? $this->perPageKey() : 4;
    return $per_page;
  }
  private function getPostsMaxPages() {
    $max_pages = $this->found_posts / $this->post_count;
    return ceil($max_pages);
  }
  
}