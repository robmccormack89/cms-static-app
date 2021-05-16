<?php
namespace Rmcc;

class TermArchiveController extends ArchiveController {
  
  public function __construct(){
    parent::__construct();
  }
  
  public function getTaxTermArchive($type, $key, $tax, $term, $taxonomies = null, $paged = false, $page = null, $posts_per_page = 4) {
    $term = new TermArchiveModel($type, $key, $tax, $term, $taxonomies, $paged, $page, $posts_per_page);
    $context['archive'] = $term->getTermArchive();

    $this->render($context, $type);
  }
}