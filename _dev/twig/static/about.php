<?php 

$context['page'] = array(
  "title" => "About",
  "slug" => "about",
);
$context['page']['msg'] = 'Hello';

// Render our view with page data
$template = $twig->load('about.twig');
echo $template->render($context);