<?php
namespace Rmcc;

class TermArchiveController extends ArchiveController {
  
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