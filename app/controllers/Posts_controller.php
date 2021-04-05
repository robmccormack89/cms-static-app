<?php

class Posts_controller extends Pages_controller {
  
  public function __construct()
  {
    parent::__construct();
  }

  public function any($slug) {
    
    $post = new Post;
    $reqPost = $post->get_post_by_slug($slug);
    
    $is_post_published = ($reqPost && $reqPost['status'] == 'published');
    $is_post_draft_allowed = ($reqPost && $reqPost['status'] == 'draft' && $this->configs['visitor_ip'] == $this->configs['author_ip']);
    $custom_post_template_exists = ($this->twig->getLoader()->exists('post-'.$slug.'.twig'));
    
    // if the post requested actually exists
    if ( $is_post_published || $is_post_draft_allowed ) {

      $context['post'] = $reqPost;

      // if custom post template exists, use that
      if ($custom_post_template_exists) {
        
        if ($this->is_cache_enabled) { $this->cache->cacheServe(); }
        echo $this->twig->render('post-'.$slug.'.twig', $context);
        if ($this->is_cache_enabled) { $this->cache->cacheFile(); }
        
      } else {
        
        // render the default post template
        if ($this->is_cache_enabled) { $this->cache->cacheServe(); }
        echo $this->twig->render('post.twig', $context);
        if ($this->is_cache_enabled) { $this->cache->cacheFile(); }
        
      }
    }
    
    if (!$reqPost) {
      // else, render the 404 template
      $this->error();
    }
    
  }
}