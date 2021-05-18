<?php
namespace Rmcc;

class TermArchiveController extends ArchiveController {
  
  public function __construct($type, $tax, $term, $paged = false, $page = null, $posts_per_page = 4){
    parent::__construct($type, $paged, $page, $posts_per_page);
    $this->tax = $tax;
    $this->term = $term;
  }
  
  public function getTaxTermArchive() {
    $term_obj = new TermArchiveModel($this->type, $this->tax, $this->term, $this->paged, $this->page, $this->posts_per_page);
    $context['archive'] = $term_obj->archive;

    $this->render($context);
  }
}