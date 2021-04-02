<?php

class Core_controller {

  public function home() {
    $context['title'] = 'Homepage';
    
    $template = $GLOBALS['twig']->load('homepage.twig');
    echo $template->render($context);
  }
  
  public function about() {
    
    $fruit = new Fruit;
    $cars = new Cars;
    $movies = new Movies;
    $context['fruits'] = $fruit->get_fruit();
    $context['cars'] = $cars->get_cars();
    $context['movies'] = $movies->get_movies();
    
    $context['title'] = 'About Page';
    
    $template = $GLOBALS['twig']->load('about.twig');
    echo $template->render($context);
  }
  
  public function page($slug) {
    
    $pages = new Page;
    $context['page'] = $pages->get_page_by_slug($slug);
    
    // render the context in  twig 
    $template = $GLOBALS['twig']->load('page.twig');
    echo $template->render($context);
  }
  
}

$Core_controller = new Core_controller;