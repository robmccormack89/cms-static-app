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
  */
  public function __construct(array $posts, array $args) {
    $this->posts = $posts; // the existing posts to be modified
    $this->args = $args; // the arguments by which to modify the existing posts
  }
  
  public function theArchivedQueryPosts() {

    // testing
    print_r($this->args);
    echo('<hr>');
    
    
    return $this->posts;
  }
  
}