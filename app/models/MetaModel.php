<?php
namespace Rmcc;
use Nahid\JsonQ\Jsonq;

// the model for getting meta data (like title & description) for an archive (like blog & categories term)
class MetaModel {
  
  public function __construct($type, $page = null, $tax = null, $term = null) {
    $this->type = $type; // type key. e.g 'blog'. required for all archive meta
    $this->page = $page; // page value for paged pages
    $this->tax = $tax; // taxonomy key. e.g 'categories'. required for getTermMeta & getCollectionMeta
    $this->term = $term; // term key. e.g 'news'. required for getTermMeta only
    if($this->tax && $this->term) {
      $this->meta = $this->getTermMeta();
    } elseif($this->tax && !($this->term)) {
      $this->meta = $this->getCollectionMeta();
    } else {
      $this->meta = $this->getArchiveMeta();
    }
  }
  
  // get the term archive meta
  protected function getCollectionMeta() {
    $q = new Jsonq('../public/json/data.min.json');
    $data = $q->from('site.content_types.'.$this->type.'.meta')->get();
    
    $data['title'] = $this->setPagedCollectionArchiveTitle($data['title'], $this->page);
    
    return $data;
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
  
  // used in getTermMeta & getArchiveMeta
  protected function setPagedArchiveTitle($data) {
    if($this->page) {
      $data = $data.' (Page '.$this->page.')';
    } else {
      $data = $data;
    }
    return $data;
  }
  
  // used in getCollectionMeta
  protected function setPagedCollectionArchiveTitle($data) {
    // if($this->tax == 'categories') {
    // 
    //   $title = 'Categories';
    // 
    // } elseif($this->tax == 'tags') {
    // 
    //   $title = 'Tags';
    // 
    // }
    
    $title = ucfirst($this->tax);
    
    if(!$this->page) {
      $data = $title;
    } else {
      $data = $title.' (Page '.$this->page.')';
    }
    return $data;
  }

}