<?php
namespace Rmcc;

class ArchiveController extends CoreController {
  
  public function __construct(){
    parent::__construct();
    $this->render = new RenderController;
  }
  
  public function blog($page = null) {
    $blog = new ArchiveModel('blog', 'posts', false, $page, 4);
    $context['archive'] = $blog->getArchive();
    
    $this->render->renderArchive('blog', $context);
  }
}