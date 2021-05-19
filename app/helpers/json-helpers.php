<?php
// these helpers are specifically related to getting json data
// any generalized functions that get json data
use Rmcc\Json;

// getting the TermTitleFromSlug for setPostsTeaseTerms() in PostsModel
// in the case of post teases or posts singulars, we will only ever know the slugs of taxonomy terms that a post has
// this function is to get the title of a term based on its slug.
// the first argument is the content type. e.g 'blog'
// the second argument is the taxonomy name. e.g 'categories'
// the third argument is the taxonomy term slug
function getTermTitleFromSlug($type, $tax, $slug) {
  $q = new Json('../public/json/data.min.json');
  $term = $q->from('site.content_types.'.$type.'.taxonomies.'.$tax)
  ->where('slug', '=', $slug)
  ->first();
  return $term['title'];
}