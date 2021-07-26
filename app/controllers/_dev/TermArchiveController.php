<?php
namespace Rmcc;

class TermArchiveController extends ArchiveController {
  
  /*
  *
  * This class is used to render taxonomy term archives of a particular taxonomy like news or media of categories
  * Term archives will display the posts from a given taxonomy term.
  * Create a new TermArchiveController object with $type, $tax, $term, $paged, $page & $posts_per_page properties. $type, $tax & $term are required
  * Call the getTaxCollectionArchive() method on the archival object to render it.
  * This is mainly for use within a routing context, see config/routes.
  * $term would usualy come from the uri request like /blog/categories/news with news = $term
  *
  */
  public function __construct(string $type, string $tax, string $term, bool $paged = false, int $page = null, int $posts_per_page = 4){
    parent::__construct($type, $paged, $page, $posts_per_page);
    $this->tax = $tax; // e.g 'categories' or 'tags'
    $this->term = $term; // e.g 'news' or 'twig'. usually wlll be from the request
  }
  
  public function getTaxTermArchive() {
    $context['archive'] = (new TermArchiveModel($this->type, $this->tax, $this->term, $this->paged, $this->page, $this->posts_per_page))->archive;
    $this->render($context);
  }
}