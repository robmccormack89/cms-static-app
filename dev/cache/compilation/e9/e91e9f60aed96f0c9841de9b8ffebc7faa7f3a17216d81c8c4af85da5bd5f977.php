<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* base.twig */
class __TwigTemplate_ea40af7839f0ec3f3ec32609aa251aba87632fcd5b7cfe6f2f2180d1c6e10bc3 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'seo_meta' => [$this, 'block_seo_meta'],
            'styles' => [$this, 'block_styles'],
            'header' => [$this, 'block_header'],
            'content' => [$this, 'block_content'],
            'footer' => [$this, 'block_footer'],
            'scripts' => [$this, 'block_scripts'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<!DOCTYPE html>
<html lang=\"";
        // line 2
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["configs"] ?? null), "language", [], "any", false, false, false, 2), "html", null, true);
        echo "\">

  <head>
    <meta charset=\"";
        // line 5
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["configs"] ?? null), "charset", [], "any", false, false, false, 5), "html", null, true);
        echo "\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=";
        // line 7
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["configs"] ?? null), "charset", [], "any", false, false, false, 7), "html", null, true);
        echo "\" />
    ";
        // line 8
        $this->displayBlock('seo_meta', $context, $blocks);
        // line 11
        echo "    <!-- theme styles -->
    <link rel=\"stylesheet\" type=\"text/css\" href=\"/public/css/base.css\">
    <link rel=\"stylesheet\" type=\"text/css\" href=\"/public/css/global.css\">
    <script src=\"/public/js/global/base.min.js\"></script>
    <!-- /theme styles -->
    ";
        // line 16
        $this->displayBlock('styles', $context, $blocks);
        // line 19
        echo "  </head>
  
  <body class=\"";
        // line 21
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["dark_light_def"] ?? null), "body_class", [], "any", false, false, false, 21), "html", null, true);
        echo "\">

    ";
        // line 23
        $this->displayBlock('header', $context, $blocks);
        // line 26
        echo "    
    <main id=\"MainContent\" role=\"main\">
      ";
        // line 28
        $this->displayBlock('content', $context, $blocks);
        // line 31
        echo "    </main>

    ";
        // line 33
        $this->displayBlock('footer', $context, $blocks);
        // line 36
        echo "    
    <!-- theme scripts -->
    <script type='text/javascript' src='/public/js/global/global.min.js'></script>
    <script type='text/javascript' src='/public/js/theme.js'></script>
    <script>
    </script>
    <!-- /theme scripts -->
    
    ";
        // line 44
        $this->displayBlock('scripts', $context, $blocks);
        // line 47
        echo "  </body>
</html>";
    }

    // line 8
    public function block_seo_meta($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 9
        echo "      <!-- seo meta should be extended on a per page basis -->
    ";
    }

    // line 16
    public function block_styles($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 17
        echo "      <!-- this block is for extending on a per page/template basis for specific page/template styles -->
    ";
    }

    // line 23
    public function block_header($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 24
        echo "      ";
        $this->loadTemplate("header.twig", "base.twig", 24)->display($context);
        // line 25
        echo "    ";
    }

    // line 28
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 29
        echo "        Sorry, Nothing to Display!!
      ";
    }

    // line 33
    public function block_footer($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 34
        echo "      ";
        $this->loadTemplate("footer.twig", "base.twig", 34)->display($context);
        // line 35
        echo "    ";
    }

    // line 44
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 45
        echo "      <!-- this block is for extending on a per page/template basis for specific page/template scipts -->
    ";
    }

    public function getTemplateName()
    {
        return "base.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  165 => 45,  161 => 44,  157 => 35,  154 => 34,  150 => 33,  145 => 29,  141 => 28,  137 => 25,  134 => 24,  130 => 23,  125 => 17,  121 => 16,  116 => 9,  112 => 8,  107 => 47,  105 => 44,  95 => 36,  93 => 33,  89 => 31,  87 => 28,  83 => 26,  81 => 23,  76 => 21,  72 => 19,  70 => 16,  63 => 11,  61 => 8,  57 => 7,  52 => 5,  46 => 2,  43 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<!DOCTYPE html>
<html lang=\"{{ configs.language }}\">

  <head>
    <meta charset=\"{{ configs.charset }}\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset={{ configs.charset }}\" />
    {% block seo_meta %}
      <!-- seo meta should be extended on a per page basis -->
    {% endblock %}
    <!-- theme styles -->
    <link rel=\"stylesheet\" type=\"text/css\" href=\"/public/css/base.css\">
    <link rel=\"stylesheet\" type=\"text/css\" href=\"/public/css/global.css\">
    <script src=\"/public/js/global/base.min.js\"></script>
    <!-- /theme styles -->
    {% block styles %}
      <!-- this block is for extending on a per page/template basis for specific page/template styles -->
    {% endblock %}
  </head>
  
  <body class=\"{{dark_light_def.body_class}}\">

    {% block header %}
      {% include 'header.twig' %}
    {% endblock %}
    
    <main id=\"MainContent\" role=\"main\">
      {% block content %}
        Sorry, Nothing to Display!!
      {% endblock %}
    </main>

    {% block footer %}
      {% include 'footer.twig' %}
    {% endblock %}
    
    <!-- theme scripts -->
    <script type='text/javascript' src='/public/js/global/global.min.js'></script>
    <script type='text/javascript' src='/public/js/theme.js'></script>
    <script>
    </script>
    <!-- /theme scripts -->
    
    {% block scripts %}
      <!-- this block is for extending on a per page/template basis for specific page/template scipts -->
    {% endblock %}
  </body>
</html>", "base.twig", "C:\\xampp\\htdocs\\static.com\\app\\views\\base.twig");
    }
}
