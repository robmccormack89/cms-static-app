<?php
namespace Rmcc;

class QueryController extends ArchiveController {
  
  public function __construct(){
    parent::__construct();
  }
  
  public function query($query_params) {
    
    $query = new QueryModel($query_params);
    $context['archive'] = $query->fetch();  
    
    // echo($context['archive']['posts']);  
    
    $this->render($context, 'search');
  }
}