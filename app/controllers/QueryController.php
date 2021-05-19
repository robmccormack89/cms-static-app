<?php
namespace Rmcc;

class QueryController extends ArchiveController {
  
  public function __construct($query){
    parent::__construct(null);
    $this->query = $query;
    $this->type = 'search';
  }
  
  public function getQueryArchive() {
    $query_obj = new QueryModel($this->query);
    $context['archive'] = $query_obj->query;

    $this->render($context);
  }
  
  public function query($query_params) {
    
    $query = new QueryModel($query_params);
    $context['archive'] = $query->fetch(); 
    
    // $context['archive']['posts'] = [0]; 
    
    $this->render($context);
  }
}