<?php
namespace Rmcc;

class CollectionArchiveController extends TermArchiveController {
  
  public function __construct($type, $tax, $paged = false, $page = null, $posts_per_page = 4){
    parent::__construct($type, $tax, '', $paged, $page, $posts_per_page);
  }
  
  public function getTaxCollectionArchive() {
    $collection_obj = new CollectionArchiveModel($this->type, $this->tax, $this->paged, $this->page, $this->posts_per_page);
    $context['archive'] = $collection_obj->archive;

    $this->render($context);
  }
}