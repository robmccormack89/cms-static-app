Singlular data & templates:

  1. All data for singular page objects is in public/json/singles.

  2. New object types added to this (like project), need to be marked with "type" field.

  3. The custom template hierarchy for singulars works as follows:

    a. if is a page, post or project:

      render _type-_slug.twig if it exists,
      else render _type.twig if it exists, 
      else render single.twig

    b. else if is any other type:

      render single-post_slug.twig if it exists, 
      else render single.twig if it exists, 
      else render 404.twig (the single.twig must exist!)
    
  4. For new types to use a custom template per page setup like above, it must be added to is_page_post_or_project(), see helpers.php.

  5. Custom construction for new types can be added with new controllers/models for example Post_controller/Post_model. New constructions for new singulars this way should extend the Single_ parent classes. This may be done for things liike new data sets etc.
  
  6. For pages that have slugs with a parent/child relationship, such as "about/test", the page will be available at the url for about/test as expected. To create a custom template for this page, create a twig file in the singles directory with the name of the slug prefixed with page- as normal (page-about/test.twig). It will instead create a folder called page-about & then a file inside that folder called test.twig. This template will then be used for the page with the slug of about/test.
  
  7. Only pages are intended for having parent/child relationships. For other types to have this uri structure to work, must be added to routes.php.
  
  8. All single objects should have a unique slug.
  
Archive data & templates:

  1. The custom template hierarchy for archives works as follows:

    a. if request if for a post,

      blog.twig if it exists,
      else archive.twig if it exists, 
      else render 404.twig (archive.twig must exist!)
    
    b. else request if for a project:

      portfolio.twig if it exists,
      else archive.twig if it exists, 
      else render 404.twig (archive.twig must exist!)
      else render 404.twig (the single.twig must exist!)
    
  2. For new types to use a custom template per page setup like above, it must be added to is_page_post_or_project(), see helpers.php.
  
Uri Routing:

  1. All uri routes are handled by routes.php. If a route doesnt exist here, it wont exist on the site. See https://phprouter.com/ for more info.

  2. Current routes are: 

    / -> (homepage/index)
    /about -> (dynamic pages)
    /about/test -> (dynamic subpages)
    /blog/lorem-ipsum -> (dynamic posts)
    /blog -> (posts archive)

template elements:

    header
    footer
    https://contentberg.theme-sphere.com/