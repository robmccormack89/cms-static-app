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
  * The first check is to see if the singular object is allowed, else throws a 404. See isSingleAllowed() for conditions when 404 errors get thrown
  * Secondly, it checks to see if a file exists using the format: $type-$slug.twig, e.g post-hello.twig, page-about.twig etc.
  * Thirdly, it checks to see if a file exists using the format: $slug.twig, e.g hello.twig, about.twig etc.
  * Fourthly, it checks to see if a file exists using the format: $type.twig, e.g page.twig, post.twig etc.
  * And lastly, if none of the above exist, it renders the singular object using single.twig
  *
  */
  protected function render($context) {
    if (isSingleAllowed($context['single'])) {
      
      $slug = slugToFilename($context['single']['slug']);
      $format1 = $this->name.'-'.$this->slug.'.twig'; // e.g post-hello.twig or page-about.twig
      $format2 = $this->slug.'.twig'; // e.g hello.twig or about.twig
      $format3 = $this->name.'.twig'; // e.g page.twig or post.twig
      
      if ($this->twig->getLoader()->exists($format1)) : $this->templateRender($format1, $context);
      elseif ($this->twig->getLoader()->exists($format2)) : $this->templateRender($format2, $context);
      elseif ($this->twig->getLoader()->exists($format3)) : $this->templateRender($format3, $context);
      else : $this->templateRender('single.twig', $context);
      endif;
      
    } else {
      $this->error();
    }
  }
}