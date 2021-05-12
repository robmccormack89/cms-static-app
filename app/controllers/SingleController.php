<?php
namespace Rmcc;

class SingleController extends CoreController {
  
  public function __construct() {
    parent::__construct();
    $this->render = new RenderController;
  }
  
  public function index() {
    $homepage = new SingleModel('page', 'index');
    $context['single'] = $homepage->getSingle();
    
    $this->render->renderSingle('index', $context);
  }
  
  public function page($slug) {
    $page = new SingleModel('page', $slug);
    $context['single'] = $page->getSingle();
    
    $this->render->renderSingle('page', $context);
  }
  
  public function post($slug) {
    $post = new SingleModel('blog', $slug, 'posts');
    $context['single'] = $post->getSingle();

    $this->render->renderSingle('post', $context);
  }
  
}