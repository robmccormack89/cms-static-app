<?php

class Page {

  // some data
  public function the_pages()
  {
    // nested array (like an array of post objects)
    $data = array(
      array(
        "id" => 1,
        "title" => "Homepage",
        "slug" => "index",
        "author" => "Robert McCormack"
      ),
      array(
        "id" => 2,
        "title" => "About page",
        "slug" => "about",
        "author" => "Robert McCormack"
      ),
      array(
        "id" => 3,
        "title" => "Contact",
        "slug" => "contact",
        "author" => "Robert McCormack"
      ),
      array(
        "id" => 4,
        "title" => "Policies",
        "slug" => "policies",
        "author" => "Robert McCormack"
      )
    );
    return $data;
  }
  
  public function get_page_by_slug($slug) {
    
    $thePages = $this->the_pages();
    $i = array_search($slug, array_column($thePages, 'slug'));
    $element = ($i !== false ? $thePages[$i] : null);
    return $element;

  }
  
}