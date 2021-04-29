<?php

class Archive_controller extends Core_controller {
  
  public function __construct()
  {
    parent::__construct();
  }
  // in progress
  public function cat_collection($page) {
    $collection = new Collection_model('blog', $page, 'categories');
  
    $context['archive'] = $collection->get_category_collection();
    $context['archive']['title'] = 'Blog - Categories';
  
    $this->render('collection.twig', $context);
  }
  // cpts tax
  public function category($term, $page) {
    $category = new Term_model('blog', $page, 'categories', $term);
    
    $context['archive'] = $category->get_category();
    
    if($context['archive']['posts']) {
      $this->render('category.twig', $context);
    } else {
      $this->error();
    }
  }
  // cpts tax
  public function tag($term, $page) {
    $tag = new Term_model('blog', $page, 'tags', $term);
    
    $context['archive'] = $tag->get_tag();
    
    if($context['archive']['posts']) {
      $this->render('tag.twig', $context);
    } else {
      $this->error();
    }
  }
  // cpts
  public function blog($page) {
    $blog = new Archive_model('blog', $page);
    
    $context['archive'] = $blog->get_blog();
    
    if($context['archive']['posts']) {
      $this->render('blog.twig', $context);
    } else {
      $this->error();
    }
  }
  // cpts
  public function portfolio($page) {
    $portfolio = new Archive_model('portfolio', $page);
    
    $context['archive'] = $portfolio->get_portfolio();
    
    if($context['archive']['posts']) {
      $this->render('portfolio.twig', $context);
    } else {
      $this->error();
    }
  }
  
  public function render($custom_template, $context) {
    if ($context['archive']) {
      if ($this->twig->getLoader()->exists($custom_template)) {
        $this->template_render($custom_template, $context);
      } else {
        $this->template_render('archive.twig', $context);
      }
    } else {
      $this->error();
    }
  }
}