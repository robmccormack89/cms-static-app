<?php
namespace Rmcc;

class CollectionArchiveController extends TermArchiveController {
  
  public function __construct(){
    parent::__construct();
  }
  
  public function getTaxCollectionArchive($type, $tax, $paged = false, $page = null, $posts_per_page = 4) {
    $collection = new CollectionArchiveModel($type, $tax, $paged, $page, $posts_per_page);
    $context['archive'] = $collection->getCollectionArchive();

    $this->render($context, $type);
  }
}