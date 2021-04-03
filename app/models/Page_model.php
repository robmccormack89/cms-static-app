<?php

class Page {

  // some data
  public function the_pages()
  {
    // nested array (like an array of post objects)
    // $data = array(
    //   array(
    //     "id" => 1,
    //     "title" => "Homepage",
    //     "slug" => "index",
    //     "status" => "published",
    //     "author" => "Robert McCormack",
    //   ),
    //   array(
    //     "id" => 2,
    //     "title" => "About page",
    //     "slug" => "about",
    //     "status" => "published",
    //     "content" => "_about-content.twig"
    //   ),
    //   array(
    //     "id" => 3,
    //     "title" => "Contact",
    //     "slug" => "contact",
    //     "status" => "published",
    //     "content" => ""
    //   ),
    //   array(
    //     "id" => 4,
    //     "title" => "Policies",
    //     "slug" => "policies",
    //     "status" => "published",
    //     "content" => "_sample-content.twig"
    //   ),
    //   array(
    //     "id" => 5,
    //     "title" => "Homepage Draft",
    //     "slug" => "homepage_draft",
    //     "status" => "draft",
    //     "author" => "Robert McCormack",
    //     "content" => "_homepage_draft-content.twig"
    //   ),
    // );
    // return $data;
    // return json_encode($data);
    
    $str = file_get_contents('../public/json/pages.json');
    $data = json_decode($str, true); // decode the JSON into an associative array
    return $data;
  }
  
  public function get_page_by_slug($slug) {
    
    $thePages = $this->the_pages();
    $i = array_search($slug, array_column($thePages, 'slug'));
    $element = ($i !== false ? $thePages[$i] : null);
    return $element;

  }
  
}