<?php
namespace Rmcc;

class SingleController extends CoreController {
  
  public function __construct($type, $slug) {
    parent::__construct();
    $this->type = $type;
    $this->slug = $slug;
    
    if($this->type == 'page') {
      $this->name = $this->type;
    } else {
      $this->name = $GLOBALS['config']['types'][$this->type]['single'];
    }
  }
  
  public function getSingle() {
    $single_obj = new SingleModel($this->type, $this->slug);
    $context['single'] = $single_obj->single;
    
    $this->render($context);
  }
  
  protected function render($context) {
    if (isSingleAllowed($context['single'])) {
      
      $slug = slugToFilename($context['single']['slug']);
      
      if ($this->twig->getLoader()->exists($this->name.'-'.$this->slug.'.twig')) {
        $this->templateRender($this->name.'-'.$this->slug.'.twig', $context);
      } elseif($this->twig->getLoader()->exists($this->slug.'.twig')) {
        $this->templateRender($this->slug.'.twig', $context);
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