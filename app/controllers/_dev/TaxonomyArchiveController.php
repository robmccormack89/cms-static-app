<?php
namespace Rmcc;

class TaxonomyArchiveController extends TermArchiveController {
  
  
  /*
  *
  * This class is used to render taxonomy archives like that for categories or tags.
  * Taxonomy archives will display the terms of a taxonomy rather than the usual posts
  * Create a new TaxonomyArchiveController object with $type, $tax, $paged, $page & $posts_per_page properties. $type & $tax are required
  * Call the getTaxCollectionArchive() method on the archival object to render it.
  * This is mainly for use within a routing context, see config/routes.
  *
  */
  public function __construct(string $type, string $tax, bool $paged = false, int $page = null, int $posts_per_page = 4){
    parent::__construct($type, $tax, '', $paged, $page, $posts_per_page);
  }
  
  public function getTaxCollectionArchive() {
    $context['archive'] = (new TaxonomyArchiveModel($this->type, $this->tax, $this->paged, $this->page, $this->posts_per_page))->archive;
    $this->render($context);
  }
}