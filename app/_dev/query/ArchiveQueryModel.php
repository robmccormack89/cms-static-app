<?php
namespace Rmcc;

class ArchiveQueryModel {
  
  // https://robertmccormack.com/blog?date=2021&categories=news,media&tags=twig,css&s=lorem&&p=2&per_page=3
  
  // this model is used to get data for a Main Index Archive that also has some query args attached to it
  // an Main Index Archive obj would be passed to it, which would be modified depending on a set of query params as see in the string above
  
  // a similar(or ideally the same) model would be necessary for Term Archives that also have some query params
  
  // the idea behind this is to allow query params to apply to Whole Archives where the query params act to filter down the results in a smart way
  
  // a separate model is being created that acts the same as Wp_Query, which can accept a string or an arguments array to give back some posts
  // this new model is meant to take care of all the getting posts stuff.
  // I should be able to just pass the whole query string on to it from here to get what I need
  // the flexibility consideration here is: the 'url' part of the uri (/blog) should act the same as the query param 'type=post' or 'type=blog'
  // so urls like https://robertmccormack.com/blog?date=2021&categories=news,media&tags=twig,css&s=lorem&&p=2&per_page=3
  // return the same as https://robertmccormack.com?date=2021&type=post&categories=news,media&tags=twig,css&s=lorem&&p=2&per_page=3
  // ideally the new model would be clean; it doesnt do the processing for this part. 
  // a url like https://robertmccormack.com/blog?date=2021&categories=news,media&tags=twig,css&s=lorem&&p=2&per_page=3
  // would need to be processed BEFORE going to the new model
  
  // any case, this class needs to be rewriiten according to the new model
  // the method query_the_posts() which creates the array to be sent to the new model, in particular, needs rewriting
  
  // as said already, the new model should be able to take the query string as an arg so this class/method should be cleaner in any case
  
  /*
  *
  * usage....
  * this class is made to modify archive objects depending on a set of custom query params
  * if no params exists that would modify the results, the given archive object is just returned
  * the other requisite property for this class would be the params string itself. 
  *
  *
  * mainly for use with the archive model. it may be neccessary to create new classes to deal with query params at other contexts...
  * like TaxonomyArchiveModel etc.
  *
  */
  public function __construct(object $archive) {
    $this->archive = $archive;
    $this->params = $_SERVER['QUERY_STRING'];
    $this->params_array = $this->get_params_array();
    $this->type = $this->get_type();
    $this->types_taxes = $this->get_types_taxes();
  }
  
  // helpers for the class basically
  public function get_params_array() {
    parse_str($this->params, $data);
    return $data;
  }
  public function get_type() {
    $type = null; // set type to blank initially
    // we need to get the $type (blog, portfolio etc) from the url part
    $stripped = trim($this->params_array['url'],'/'); // remove trailing & leading slashes first if exist
    $exploded = explode('/', $stripped); // explode the remains in parts
    if($exploded[0]) {
      $type = $exploded[0]; // the first part should be the typ
    }
    return $type;
  }
  public function get_types_taxes() {
    $active_taxes = null;
    if($this->type){
      $active_taxes = $GLOBALS["config"]['types'][$this->type]['taxonomies'];
    }
    return $active_taxes;
  }

  /**
   * returns a given archive object, either modified depending on query params, or not.
   *
   * @return object
   */
  public function get_queried_archive() {
    // check if we are good to go, see method below. then we do our stuff to the archive obj
    if($this->archive_is_queryable()) :
      
    // do some test stuff to confirm. change the archive's title & description
    $this->archive['title'] = 'We are motoring';
    // $this->archive['description'] = $this->params;
    
    // need to modify the archive obj in 3 parts; meta, posts & pagination
    // do the meta last; least important
    // set the pagination to blank & do the posts first.
    
    $this->archive['pagination'] = null;
    
    // mod the posts. so we can now see posts is a php array rather than a Json object
    $this->archive['posts'] = $this->query_the_posts($this->archive['posts']);
    
    endif;
    return $this->archive; // return the archive obj, either modified or the same as before. 
  }
  
  // we will create an array of data to send to ArchiveQueryPostsModel with everything it needs to get the posts according to the query params
  public function query_the_posts($posts) {
    
    // create the empty $args array
    $args = $this->create_args_array();
    $null_args = null;
    
    // a simpler class can be created from this new class that just gets posts depending on the params conditions, not modify existing posts
    // that would be like the Query class as created before. Such a class can be used for the search archive but also for general posts queries
    // would be the QueryPostsModel
    $posts = (new QueryPostsModel())->posts;
        
    // echo($posts);
    // echo('<hr>');
    return $posts;
  }
  
  public function create_args_array() {
    // create the empty $args array
    $args = array();
    // adds 'hello' to the $args array
    // next, we fill the $args array with some data.
    // if the hello query param exists in the query string... (hello=there)
    if(isset($this->params_array['hello'])) {
      // add the value for the hello query param to the $args array
      $args['hello'] = $this->params_array['hello'];
    }
    // adds 'type' to the $args array
    // if the url query param exists in the query string...
    // by default this should be the case as we are applying this to main index archives where the url part would be 'blog' or '/blog/page/2'
    // but if we use this class to do queries on the base url, this will need to be different.
    // in that case we can refer back to the original Query model
    if($this->type){
      $args['type'] = $this->type;
    }
    // adds 'taxonomies' to the $args array. but only if type exists first
    // if there are indeed active taxonomies registered according to the given type
    if($this->types_taxes){
      // create the array placeholder for the 'taxonomies' of the $args array
      $args['taxonomies'] = array();
      // loop thru the active taxes & set them to $args array with the values taken from the query string
      foreach($this->types_taxes as $tax) {
        // if that particular taxonomy has a query param in the url
        if(isset($this->params_array[$tax['key']])) {
          // set the value of that query param into the $args array under 'taxonomies'
          // if query param value has a comma, it must be two values. in that case, split into an array & store that to the $args array
          if (strpos($this->params_array[$tax['key']], ',') !== false) {
            $terms_array = explode(',', $this->params_array[$tax['key']]);
            $args['taxonomies'][$tax['key']] = $terms_array;
          } else {
            // or just store the single item
            $args['taxonomies'][$tax['key']] = $this->params_array[$tax['key']];
          }
        }
      }
    }
    // adds 'query' to the $args array
    // adding query stuff to the args array. if search or date params exist in the uri...
    if(isset($this->params_array['s']) || isset($this->params_array['date'])) {
      
      // put a property into the args called query which will be an array
      $args['query'] = array();
      
      // if search param exists in uri, add this to the query array
      if(isset($this->params_array['s'])) {
       $args['query']['s'] = $this->params_array['s'];
      }
      
      // if date param exists in uri, add this to the query array
      if(isset($this->params_array['date'])) {
       $args['query']['date'] = $this->params_array['date'];
      }
      
    }
    // adds 'pagination' to the $args array
    // adding pagination stuff to the args array.
    if(isset($this->params_array['p']) || isset($this->params_array['per_page']) || isset($this->params_array['show_all'])) {
      
      $args['pagination'] = array();
      
      // if 'paged' param exists, set paged to true, else set it to false
      // paged is a boolean setting. whether or it it should be paged.
      // it might be easier to forgoe this setting and just used show_all
      // becuase the default should be a paged archive anyways. there should be no need to tell it to be paged
      // there should only be a need to tell it to show_all, which would override the default setting
      // if(isset($this->params_array['paged'])) {
      //  $args['pagination']['paged'] = $this->params_array['paged'];
      // } else {
      // 
      // }
      
      // show_all is a modifier to just show all posts. it should disregard other 'per_page' & 'p' settings & force the show_all if present
      // if show_all exists add that to the args array, else we add 'p' & 'per_page'
      // because if show_all does exist there is no need for any other pagination args
      if(isset($this->params_array['show_all'])) {
       $args['pagination']['show_all'] = true;
      } else {
       // requested paginated page. if none, set it to null for now. default could need to be 0 or 1. lets see....
       if(isset($this->params_array['p'])) {
        $args['pagination']['p'] = $this->params_array['p'];
       } else {
        $args['pagination']['p'] = null;
       }
       // setting for how many posts to show per page. default would be 4. this argument is a modifier
       // so if 'per_page' exists in the string, use that setting, else set it to 4
       if(isset($this->params_array['per_page'])) {
        $args['pagination']['per_page'] = $this->params_array['per_page'];
       } else {
        $args['pagination']['per_page'] = 4;
       }
     }
      
    }
    
    return $args;
  }
  
  /**
   * check to see if is safe to process the query params & modifiy the archive obj; 
   * check if any params exists & make sure its not a paginated archive etc...
   * also any other conditions that might throw a false & just return the archive unmodified
   *
   * @return bool
   */
  public function archive_is_queryable() {
    
    // check to make sure its not a standard paginated archive
    if (strpos($this->params, '/page/') !== false) return false;
    
    // check to make sure that at least one query param actually exists.
    if(!$this->selected_query_params_exist()) return false;
    
    return true; // if none of the above conditions return false, then go ahead!
  }
  /**
   * checks to see if any query params exist in the query string
   *
   * @return bool
   */
  public function selected_query_params_exist() {
    $string_params_array = array_keys($this->params_array); // returns an array with all keys from $data(the params array) as values. to check against!!
    
    // new array. this contains all our custom values to check for in the params.
    // dynamic values like taxonomies can get added below
    $custom_params_array = array(
      'hello', // testing
      'p', 'per_page', 'show_all', // pagination params
      's', 'date' // other params
    );
    
    // if the type has some taxes, we will add that to the new array
    if($this->types_taxes){
      $taxes_array = array_keys($this->types_taxes); //  returns array with types(blog,portfolio) as values
      // loop the taxes & add them to the params array
      foreach($taxes_array as $tax){
        array_push($custom_params_array, $tax); // adds the taxes to the end of params array
      }
    }
    
    // if our custom params and the query string params have any in common
    $c = array_intersect($string_params_array, $custom_params_array);
    if (count($c) > 0) {
      return true; // there is at least one equal value so return true
    }
  }
  
  /**
  * check if any of the correct query paramaters exist in the string first, e.g categories tags etc. if exist, continue
  *
  * things to be modified when query params do exist: the posts, the pagination, maybe the meta
  *
  * the meta needs new data relating to reset filters. when a query param is present, there will need to be reset filters.
  * at something like archive.reset_filters, can be an array of items with title/label & url, that can be looped thru
  * each item in the loop will be a reset button used to reset a particular query paramter. it will just link to same current url minus that parameter
  *
  * the posts obviously need to be modified if there are any query parameters which would actively change the results
  * i would obviously be acting upon the initial posts, and filtering them according to the params
  * this may be done by turning the posts into a Json object & querying them with the Json class, then turn them back again
  * or possibly i can just use plain php to modify the posts object. see what form the data is in first. if it already is a Json object, then use Json query
  *
  * lastly the pagination data would then need to be modified when query params exist as any at all that change the results, also change the pagination
  * some query params will specifically be for pagination, like posts_per_page, show_all & p
  * if a query param exists on an already paginatinated url like blog/page/2, nothing should happen. params should only work on non paginated urls
  * doing so otherwise would create a need to redirect to new urls, which is messier than needed
  * links with query params, like filter buttons, will only link to non paginated urls.
  * they will ignore pagination if it exists in any form
  * meaning if on a url like 'blog?categories=news&date=2021&p=4' & I click a filter to change category to media, 
  * it will bring me to 'blog?categories=media&date=2021'
  * or if im on a url like 'blog/page/2' & I click to filter those results by category media, 
  * it will bring me to 'blog?categories=media'
  * retaining the main query params but dropping the pagination back to the beginning. this is logical.
  * when query params do exist tho, the pagination data will be modified to work off query params for paged links, rather than page params
  * meaning pagination links after query params applied will look like 'blog?categories=media&p=2' or 'blog?categories=news&date=2021&p=4'
   */
  
}