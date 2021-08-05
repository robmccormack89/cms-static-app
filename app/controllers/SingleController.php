<?php
namespace Rmcc;

class SingleController extends CoreController {
  
  /*
  *
  * This class is used to render singular objects like pages, posts, projects etc.
  * Create a new SingleController object with $type & $slug properties.
  * Call the getSingle() method on the object to render it.
  * This is mainly for use within a routing context, see config/routes.
  *
  */
  public function __construct(string $type, string $slug) {
    parent::__construct();
    $this->type = $type; // e.g 'page' or 'blog' or 'portfolio'
    $this->slug = $slug; // e.g 'about'. this will usually come from the request unless setting for specific pages
    // the $name property is only used for render() to differenciate between archived & non-archived singular objects
    $this->name = ($this->type == 'page') ? $this->type : $GLOBALS['config']['types'][$this->type]['single'];
    
    $this->init();
  }
  
  private function init() {
    global $_context;
    $_context = array(
      'single' => 'Single',
      'type' => $this->type,
      'slug' => $this->slug,
      'name' => $this->name,
    );
  }
  
  public function getSingle() {
    $context['single'] = (new SingleModel($this->type, $this->slug))->single;
    $context['context'] = $GLOBALS['_context'];
    $this->render($context);
  }
  
  /*
  *
  * This method is used to render singular objects according to a template hierarchy.
  *
  */
  protected function render($context) {
    if (isSingleAllowed($context['single'])) {
      
      $_type = (isset($context['single']['type'])) ? $context['single']['type'] : $this->name;
      $_format = (isset($context['single']['format'])) ? $context['single']['format'] : 'default';
      $_slug = $context['single']['slug'];
      
      $format1 = $_type.'-'.$_slug.'.twig'; // post-lorem-ipsum-dolor.twig
      $format2 = $_type.'_'.$_format.'.twig'; // post_video.twig
      $format3 = $_slug.'.twig'; // hello.twig||index.twig
      $format4 = $_type.'.twig'; // post.twig
      
      if($this->twig->getLoader()->exists($format1)){
        $this->templateRender($format1, $context);
      }

      elseif($this->twig->getLoader()->exists($format2)) {
        $this->templateRender($format2, $context);
      }

      elseif($this->twig->getLoader()->exists($format3)) {
        $this->templateRender($format3, $context);
      }
      
      elseif($this->twig->getLoader()->exists($format4)) {
        $this->templateRender($format4, $context);
      }
      
      else {
        $this->templateRender('single.twig', $context);
      }
      
    } else {
      $this->error();
    }
  }
}