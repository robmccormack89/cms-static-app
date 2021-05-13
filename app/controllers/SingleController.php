<?php
namespace Rmcc;

class SingleController extends CoreController {
  
  public function __construct($name, $type, $slug, $key = null) {
    parent::__construct();
    $this->name = $name; // string. twig template name. e.g 'page', 'post' or 'project'
    $this->type = $type; // string. e.g 'page' or 'blog'. if set to 'page', $key is unnecessary
    $this->slug = $slug; // string. requested page slug. passed on via routes
    $this->key = $key; // string. e.g 'posts' or 'projects'. the plural items key for the archived content type.
    
    $this->single = new SingleModel($this->name, $this->type, $this->slug, $this->key);
  }
  
  public function getSingle() {
    $context['single'] = $this->single->getSinglular();
    
    $this->render($context);
  }
  
  protected function render($context) {
    if (isSingleAllowed($context['single'])) {
      $slug = slugToFilename($context['single']['slug']);
      if ($this->twig->getLoader()->exists($this->name.'-'.$slug.'.twig')) {
        $this->templateRender($this->name.'-'.$slug.'.twig', $context);
      } elseif ($this->twig->getLoader()->exists($this->name.'.twig')) {
        $this->templateRender($this->name.'.twig', $context);
      } else {
        $this->templateRender('single.twig', $context);
      };
    } else {
      $this->error();
    }
  }
}