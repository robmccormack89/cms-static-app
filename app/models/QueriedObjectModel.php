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
class QueriedObjectModel {
  
  public function __construct(
    string $type, // type key. e.g 'blog'. required for all archive meta
    int $page = null, // page value for paged pages. will usually come from the request
    string $tax = null, // taxonomy key. e.g 'categories'. required for getTermMeta & getCollectionMeta
    string $term = null // term key. e.g 'news'. required for getTermMeta only
  ) 
  {
    $this->type = $type; 
    $this->page = $page; 
    $this->tax = $tax; 
    $this->term = $term; 
    
    $this->data = $this->getData(); // this property then contains all our data
  }
  
  private function getData() {
    if($this->tax && $this->term) {
      $data = $this->getTermMeta();
    } elseif($this->tax && !($this->term)) {
      $data = $this->getCollectionMeta();
    } else {
      $data = $this->getArchiveMeta();
    }
    return $data;
  }
  
  // get the archive meta data
  private function getArchiveMeta() {
    $q = new Json('../public/json/data.min.json');
    $data = $q->from('site.content_types.'.$this->type.'.meta')->get();
    if($this->page > 1) $data['title'] = $data['title'].' (Page '.$this->page.')';
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
    if($this->page > 1) $data['title'] = $data['title'].' (Page '.$this->page.')';
    return $data;
  }
  
  // get the term archive meta
  private function getCollectionMeta() {
    $q = new Json('../public/json/data.min.json');
    $data = $q->from('site.content_types.'.$this->type.'.meta')->get();
    
    $title = ucfirst($this->tax);
    $data['title'] = ($this->page > 1) ? $title.' (Page '.$this->page.')' : $title;
    
    return $data;
  }

}