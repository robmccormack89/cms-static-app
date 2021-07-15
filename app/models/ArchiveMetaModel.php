<?php
namespace Rmcc;

/*
*
* This class is used for getting meta data (like title & description) for an archive (like blog & categories term)
* Create a new ArchiveMetaModel object with $type, $page, $tax & $term properties.
* $type is a required property
* The $meta property of the created object contains all the data for the archive meta item
*
*/
class ArchiveMetaModel {
  
  public function __construct(string $type, int $page = null, string $tax = null, string $term = null) {
    $this->type = $type; // type key. e.g 'blog'. required for all archive meta
    $this->page = $page; // page value for paged pages. will usually come from teh request
    $this->tax = $tax; // taxonomy key. e.g 'categories'. required for getTermMeta & getCollectionMeta
    $this->term = $term; // term key. e.g 'news'. required for getTermMeta only
    
    $this->meta = $this->getMeta(); // this property then contains all our data
  }
  
  //
  private function getMeta() {
    if($this->tax && $this->term) {
      $data = $this->getTermMeta();
    } elseif($this->tax && !($this->term)) {
      $data = $this->getCollectionMeta();
    } else {
      $data = $this->getArchiveMeta();
    }
    return $data;
  }
  
  // get the term archive meta
  private function getCollectionMeta() {
    $q = new Json('../public/json/data.min.json');
    $data = $q->from('site.content_types.'.$this->type.'.meta')->get();
    
    $data['title'] = $this->setPagedCollectionArchiveTitle($data['title'], $this->page);
    
    return $data;
  }
  
  // get the archive meta data
  private function getArchiveMeta() {
    $q = new Json('../public/json/data.min.json');
    $data = $q->from('site.content_types.'.$this->type.'.meta')->get();
    
    $data['title'] = $this->setPagedArchiveTitle($data['title'], $this->page);
    
    return $data;
  }
  
  // get the term archive meta
  private function getTermMeta() {
    
    if($this->term == null) {

      $q = new Json('../public/json/data.min.json');
      $data = $q->from('site.'.$this->type.'.meta')->get();
      
    } elseif($this->term) {
      
      $q = new Json('../public/json/data.min.json');
      $data = $q->from('site.content_types.'.$this->type.'.taxonomies.'.$this->tax)
      ->where('slug', '=', $this->term)->first();
    }
    
    $data['title'] = $this->setPagedArchiveTitle($data['title']);
    
    return $data;
  }
  
  // used in getTermMeta & getArchiveMeta
  private function setPagedArchiveTitle($data) {
    if($this->page) {
      $data = $data.' (Page '.$this->page.')';
    } else {
      $data = $data;
    }
    return $data;
  }
  
  // used in getCollectionMeta
  private function setPagedCollectionArchiveTitle($data) {
    $title = ucfirst($this->tax);
    
    if(!$this->page) {
      $data = $title;
    } else {
      $data = $title.' (Page '.$this->page.')';
    }
    return $data;
  }

}