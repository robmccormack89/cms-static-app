<?php
namespace Rmcc;

// for render twig templates
class RenderController extends CoreController {
  
  public function __construct() {
    parent::__construct();
  }
  
  public function renderSingle($template_name, $context) {
    if (isSingleAllowed($context['single'])) {
      $slug = slugToFilename($context['single']['slug']);
      if ($this->twig->getLoader()->exists($template_name.'-'.$slug.'.twig')) {
        $this->templateRender($template_name.'-'.$slug.'.twig', $context);
      } elseif ($this->twig->getLoader()->exists($template_name.'.twig')) {
        $this->templateRender($template_name.'.twig', $context);
      } else {
        $this->templateRender('single.twig', $context);
      };
    } else {
      $this->error();
    }
  }
  
  public function renderArchive($template_name, $context) {
    if ($this->twig->getLoader()->exists($template_name.'.twig')) {
      $this->templateRender($template_name.'.twig', $context);
    } else {
      $this->templateRender('archive.twig', $context);
    }
  }
  
  public function templateRender($template, $context) {
    Cache::cacheRender($this->twig->render($template, $context));
  }
  
}