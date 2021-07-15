<?php
namespace Rmcc;

class ArchiveQueryModel {
  
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
    $this->params = $_SERVER['QUERY_STRING'];;
    $this->archive = $archive;
  }

  /**
   * returns a given archive object, either modified depending on query params, or not.
   * @return object
   */
  public function doMe() {
    // check if we are good to go, see method below. then we do our stuff to the archive obj
    if($this->areWeGoodToGo()) :
      
    // do some test stuff to confirm
    $this->archive['title'] = 'Yooo!';
    print_r($this->params.' <hr>');
    
    // need to modify the archive obj in 3 parts; meta, posts & pagination
    // do the meta last; least important
    // set the pagination to blank & do the posts first.
    
    $this->archive['pagination'] = null;
    
    // mod the posts. so posts is an array
    $this->archive['posts'] = $this->queryThePosts($this->archive['posts']);
    
    endif;
    return $this->archive; // return the archive obj, either modified or the same as before. 
  }
  
  public function queryThePosts($posts) {
    
    // print_r($posts);
    // echo('<hr>');
    
    // taxonomies to use as query params. we may get this from the config types
    // in any case taxex need to be dealt with dynamically
    $taxonomies = array(
      'categories' => $this->get_date_params(),
      'tags' => $this->get_tag_params(),
      'technologies' => $this->get_tech_params(),
    );
    
    $args = array(
      
      // test
      'hello' => $this->get_hello_params(),
      
      // filtering params like search & date
      'query' => array(
        'search' => $this->get_search_params(),
        'date' => $this->get_date_params(),
      ),
      
      // taxonomy params must be dynamic, see above
      'taxonomies' => $taxonomies,
      
      // pagination params keep them separate. must allow for show_all
      'pagination' => array(
        'paged' => $this->get_paged_params(),
        'page' => $this->get_page_params(),
        'per_page' => $this->get_per_page_params(),
      ),
    );
    // a simpler class can be created from this new class that just gets posts depending on the params conditions, not modify existing posts
    // that would be like the Query class as created before. Such a class can be used for the search archive but also for general posts queries
    // would be the QueryPostsModel
    $posts = (new ArchiveQueryPostsModel($posts, $args))->theArchivedQueryPosts();
        
    return $posts;
  }
  
  /**
   * check to see if is safe to process the query params & modifiy the archive obj; 
   * check if any params exists & make sure its not a paginated archive etc...
   * also any other conditions that might throw a false & just return the archive unmodified
   *
   * @return bool
   */
  public function areWeGoodToGo() {
    
    // check to make sure its not a standard paginated archive
    if (strpos($this->params, '/page/') !== false) return false;
    
    // check to make sure that at least on query param actually exists
    if(!$this->if_any_query_params()) return false;
    
    return true; // if none of the above conditions return false, then go ahead!
  }
  /**
   * checks to see if any query params exist in the query string
   *
   * @return bool
   */
  public function if_any_query_params() {
    parse_str($this->params, $data);
    
    $is_hello_params = (isset($data['hello']));
    $is_paged_params = (isset($data['show_all']) || isset($data['p']) || isset($data['per_page']));
    $is_rest_params = (isset($data['s']) || isset($data['date']) || isset($data['cat']) || isset($data['tag']));
    
    if ($is_hello_params || $is_paged_params || $is_rest_params) return true;
  }
  
  /**
   * functions to get the values of the various query param keys
   *
   * @return string
   */
   
  // test
  public function get_hello_params() {
    parse_str($this->params, $data);
    if (isset($data['hello'])) :
      return $data['hello'];
    endif;
  }
  
  // pagination
  public function get_paged_params() {
    parse_str($this->params, $data);
    if (isset($data['show_all']) && !isset($data['p'])) :
      return false;
    else:
      return true;
    endif;
  }
  public function get_page_params() {
    parse_str($this->params, $data);
    if (isset($data['p'])) :
      return $data['p'];
    else:
      return null;
    endif;
  }
  public function get_per_page_params() {
    parse_str($this->params, $data);
    if (isset($data['per_page'])) :
      return $data['per_page'];
    else:
      return 4;
    endif;
  }
  
  // filter query
  public function get_search_params() {
    parse_str($this->params, $data);
    if (isset($data['s'])) :
    return $data['s'];
    endif;
  }
  public function get_date_params() {
    parse_str($this->params, $data);
    if (isset($data['date'])) :
    return $data['date'];
    endif;
  }
  
  // taxonomy
  public function get_category_params() {
    parse_str($this->params, $data);
    if (isset($data['categories'])) :
      return $data['categories'];
    endif;
  }
  public function get_tag_params() {
    parse_str($this->params, $data);
    if (isset($data['tags'])) :
      return $data['tags'];
    endif;
  }
  public function get_tech_params() {
    parse_str($this->params, $data);
    if (isset($data['technologies'])) :
      return $data['technologies'];
    endif;
  }
  
  
  // check if any of the correct query paramaters exist in the string first, e.g categories tags etc. if exist, continue
  
  // things to be modified when query params do exist: the posts, the pagination, maybe the meta
  
  // the meta needs new data relating to reset filters. when a query param is present, there will need to be reset filters.
  // at something like archive.reset_filters, can be an array of items with title/label & url, that can be looped thru
  // each item in the loop will be a reset button used to reset a particular query paramter. it will just link to same current url minus that parameter
  
  // the posts obviously need to be modified if there are any query parameters which would actively change the results
  // i would obviously be acting upon the initial posts, and filtering them according to the params
  // this may be done by turning the posts into a Json object & querying them with the Json class, then turn them back again
  // or possibly i can just use plain php to modify the posts object. see what form the data is in first. if it already is a Json object, then use Json query
  
  // lastly the pagination data would then need to be modified when query params exist as any at all that change the results, also change the pagination
  // some query params will specifically be for pagination, like posts_per_page, show_all & p
  // if a query param exists on an already paginatinated url like blog/page/2, nothing should happen. params should only work on non paginated urls
  // doing so otherwise would create a need to redirect to new urls, which is messier than needed
  // links with query params, like filter buttons, will only link to non paginated urls.
  // they will ignore pagination if it exists in any form
  // meaning if on a url like 'blog?categories=news&date=2021&p=4' & I click a filter to change category to media, 
  // it will bring me to 'blog?categories=media&date=2021'
  // or if im on a url like 'blog/page/2' & I click to filter those results by category media, 
  // it will bring me to 'blog?categories=media'
  // retaining the main query params but dropping the pagination back to the beginning. this is logical.
  // when query params do exist tho, the pagination data will be modified to work off query params for paged links, rather than page params
  // meaning pagination links after query params applied will look like 'blog?categories=media&p=2' or 'blog?categories=news&date=2021&p=4'
  
}