<?php
namespace Rmcc;

class QueriedObjectModel {
  
  public function __construct(
    string $type = null, // type. e.g 'blog'. required
    string $tax = null, // taxonomy. e.g 'categories'. required for getTermMeta
    string $term = null // term. e.g 'news'. required for getTermMeta
  ) 
  {
    $this->type = $type; 
    $this->tax = $tax; 
    $this->term = $term; 
  }
  
  public function getQueriedObject() {
    if(!$this->type) $data = $this->getBaseMeta();
    if($this->type && !$this->term) $data = $this->getArchiveMeta();
    if($this->type && $this->term) $data = $this->getTermMeta();
    return $data;
  }
  
  // get the archive meta. if no type, use the site's base meta
  private function getBaseMeta() {
    $q = new Json('../public/json/data.min.json');
    $data = $q->from('site.meta')->get();
    return $data;
  }
  
  // get the archive meta from type (blog, portfolio etc)
  private function getArchiveMeta() {
    $q = new Json('../public/json/data.min.json');
    $data = $q->from('site.content_types.'.$this->type.'.meta')->get();
    return $data;
  }
  
  // get the archive meta (term)
  private function getTermMeta() {
    if($this->term) {
      $q = new Json('../public/json/data.min.json');
      $data = $q->from('site.content_types.'.$this->type.'.taxonomies.'.$this->tax)
      ->where('slug', '=', $this->term)->first();
    } else {
      $q = new Json('../public/json/data.min.json');
      $data = $q->from('site.'.$this->type.'.meta')->get();
    }
    return $data;
  }

}