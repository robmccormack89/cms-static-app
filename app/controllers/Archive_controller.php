<?php

class Archive_controller extends Core_controller {
  
  public function __construct()
  {
    parent::__construct();
  }
  
  public function query($query_params) {
    
    $query = new Query_model($query_params);
    
    $context['archive'] = $query->fetch();  
    
    // echo($context['archive']['posts']);  
    
    $this->render('search', $context);
  }
  
  // taxonomy collections (archive of terms)
  public function cat_collection($page) {
    $collection = new Collection_model('blog', $page, 'categories');
    $context['archive'] = $collection->get_collection();

    if($context['archive']['posts']) {
      $this->render('collection.twig', $context);
    } else {
      $this->error();
    }
  }
  
  // taxonomy collections (archive of terms)
  public function tag_collection($page) {
    $collection = new Collection_model('blog', $page, 'tags');
    $context['archive'] = $collection->get_collection();

    if($context['archive']['posts']) {
      $this->render('collection.twig', $context);
    } else {
      $this->error();
    }
  }
  
  // type archive
  public function blog($page) {
    $blog = new Archive_model('blog', $page);
    $context['archive'] = $blog->get_archive();
    
    if($context['archive']['posts']) {
      $this->render('blog', $context);
    } else {
      $this->error();
    }
  }
  
  // type archive
  public function portfolio($page) {
    $portfolio = new Archive_model('portfolio', $page);
    $context['archive'] = $portfolio->get_archive();
    
    if($context['archive']['posts']) {
      $this->render('portfolio', $context);
    } else {
      $this->error();
    }
  }
  
  // term archive
  public function category($term, $page) {
    $category = new Term_model('blog', $page, 'categories', $term);
    
    $context['archive'] = $category->get_term();    
    
    if($context['archive']['posts']) {
      $this->render('category', $context);
    } else {
      $this->error();
    }
  }
  
  // term archive
  public function tag($term, $page) {
    $tag = new Term_model('blog', $page, 'tags', $term);
    
    $context['archive'] = $tag->get_term();    
    
    if($context['archive']['posts']) {
      $this->render('tag', $context);
    } else {
      $this->error();
    }
  }
  
  // renderer
  public function render($template_name, $context) {
    if ($this->twig->getLoader()->exists($template_name.'.twig')) {
      $this->template_render($template_name.'.twig', $context);
    } else {
      $this->template_render('archive.twig', $context);
    }
  }
}