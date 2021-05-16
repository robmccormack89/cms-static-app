<?php
namespace Rmcc;
use Nahid\JsonQ\Jsonq;

// the model for getting meta data (like title & description) for an archive (like blog & categories term)
class MetaModel {
  
  public function __construct($type, $page = null, $tax = null, $term = null) {
    $this->type = $type;
    $this->page = $page;
    $this->tax = $tax;
    $this->term = $term;
    if($this->tax) {
      $this->meta = $this->getTermMeta();
    } else {
      $this->meta = $this->getArchiveMeta();
    }
  }
  
  // get the archive meta data
  protected function getArchiveMeta() {
    $q = new Jsonq('../public/json/data.min.json');
    $data = $q->from('site.content_types.'.$this->type.'.meta')->get();
    
    $data['title'] = $this->setPagedArchiveTitle($data['title'], $this->page);
    
    return $data;
  }
  
  // get the term archive meta
  protected function getTermMeta() {
    
    if($this->term == null) {

      $q = new Jsonq('../public/json/data.min.json');
      $data = $q->from('site.'.$this->type.'.meta')->get();
      
    } elseif($this->term) {
      
      $q = new Jsonq('../public/json/data.min.json');
      $data = $q->from('site.content_types.'.$this->type.'.taxonomies.'.$this->tax)
      ->where('slug', '=', $this->term)->first();
    }
    
    $data['title'] = $this->setPagedArchiveTitle($data['title']);
    
    return $data;
  }
  
  // used in MetaModel
  protected function setPagedArchiveTitle($data) {
    if($this->page) {
      $data = $data.' (Page '.$this->page.')';
    } else {
      $data = $data;
    }
    return $data;
  }

}