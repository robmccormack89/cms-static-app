<?php
namespace Rmcc;

class TaxonomyArchiveController extends TermArchiveController {
  
  public function __construct(string $type, string $tax, bool $paged = false, int $page = null, int $posts_per_page = 4){
    parent::__construct($type, $tax, '', $paged, $page, $posts_per_page);
  }
  
  public function getTaxCollectionArchive() {
    $context['archive'] = (new TaxonomyArchiveModel($this->type, $this->tax, $this->paged, $this->page, $this->posts_per_page))->archive;
    $this->render($context);
  }
}