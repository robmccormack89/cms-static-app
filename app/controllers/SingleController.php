<?php
namespace Rmcc;

class SingleController extends CoreController {
  
  public function __construct() {
    parent::__construct();
  }
  
  public function getSingle($name, $type, $slug, $key = null) {
    $single = new SingleModel($name, $type, $slug, $key);
    $context['single'] = $single->getSinglular();
    
    $this->render($context, $name, $slug);
  }
  
  protected function render($context, $name, $slug) {
    if (isSingleAllowed($context['single'])) {
      $slug = slugToFilename($context['single']['slug']);
      if ($this->twig->getLoader()->exists($name.'-'.$slug.'.twig')) {
        $this->templateRender($name.'-'.$slug.'.twig', $context);
      } elseif($this->twig->getLoader()->exists($slug.'.twig')) {
        $this->templateRender($slug.'.twig', $context);
      } elseif ($this->twig->getLoader()->exists($name.'.twig')) {
        $this->templateRender($name.'.twig', $context);
      } else {
        $this->templateRender('single.twig', $context);
      };
    } else {
      $this->error();
    }
  }
}