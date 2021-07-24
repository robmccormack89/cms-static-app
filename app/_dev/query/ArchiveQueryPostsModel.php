<?php
namespace Rmcc;

class ArchiveQueryPostsModel {
  
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
  
  // Array ( 
  //   [hello] => boo // testing
  //   [type] => blog // if type empty, query all types
  //   [tax_query] => Array ( 
  //     [categories] => Array ( 
  //       [0] => news 
  //       [1] => media 
  //     ) 
  //     [tags] => Array ( 
  //       [0] => twig 
  //       [1] => css 
  //     ) 
  //   ) 
  //   [query] => Array ( 
  //     [s] => shhhh 
  //     [date] => there 
  //   ) 
  //   [pagination] => Array ( 
  //     [p] => 2 
  //     [per_page] => 3 
  //   ) 
  // )
      
  public function __construct(array $posts, array $args) {
    $this->posts = $posts; // just leave these in for now. it is not necessary anymore to moify existing posts
    $this->args = $args; // the arguments by which to modify the existing posts
    
    $this->hello = $this->get_hello_arg();
  }
  
  public function get_hello_arg() {
    $data = null;
    if(isset($this->args['hello'])) {
      $data = $this->args['hello'];
    };
    return $data;
  }
  
  public function theArchivedQueryPosts() {

    // testing. if hello is present, do something.
    if($this->hello) print_r($this->hello.'<hr>');
    
    print_r($this->args);
    echo('<hr>');
    
    return $this->posts;
  }
  
  // private function getAllQueryPosts() {
  //   $q = new Json('../public/json/data.min.json');
  //   $posts = $q->find('site.content_types.'.$this->type.'.'.$this->key)
  //   ->get();
  // 
  //   $data = $this->setPostsTease($posts);
  // 
  //   return $data;
  // }
  
}