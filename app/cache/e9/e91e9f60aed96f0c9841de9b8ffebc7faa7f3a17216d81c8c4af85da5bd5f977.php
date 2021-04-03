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
<html lang=\"en\">

  <head>
    <meta charset=\"utf-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
    <title>";
        // line 7
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["site"] ?? null), "title", [], "any", false, false, false, 7), "html", null, true);
        echo " | ";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["site"] ?? null), "tagline", [], "any", false, false, false, 7), "html", null, true);
        echo "</title>
    <link rel=\"preconnect\" href=\"https://fonts.gstatic.com\">
    <link href=\"https://fonts.googleapis.com/css2?family=PT+Serif&family=Poppins:wght@400;700&display=swap\" rel=\"stylesheet\">
    <link rel=\"stylesheet\" type=\"text/css\" href=\"/public/css/base.css\">
    <link rel=\"stylesheet\" type=\"text/css\" href=\"/public/css/global.css\">
    <script src=\"/public/js/global/base.min.js\"></script>
    ";
        // line 13
        $this->displayBlock('styles', $context, $blocks);
        // line 15
        echo "    <style>
      /* font stylings */
      html,
      .pt-serif {
        font-family: 'PT Serif', serif;
      }
      .uk-h1, .uk-h2, .uk-h3, .uk-h4, .uk-h5, .uk-h6, 
      .uk-heading-2xlarge, .uk-heading-large, .uk-heading-medium, .uk-heading-small, .uk-heading-xlarge, h1, h2, h3, h4, h5, h6,
      .uk-logo {
        font-family: 'Poppins', sans-serif;
        letter-spacing: -1px;
        font-weight: 700;
        word-spacing: 2px;
      }
      .poppins, 
      .site-tagline, 
      .uk-article-meta, 
      .uk-label, 
      .uk-navbar-item, .uk-navbar-nav>li>a, .uk-navbar-toggle,
      .uk-button {
        font-family: 'Poppins', sans-serif;
      }
      .uk-button {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        letter-spacing: -1px;
        word-spacing: 1px;
      }
      /* reset bold elements */
      .uk-text-lead {
        font-size: 1.3rem;
      }
      .uk-light .uk-text-lead {
        color: #fff;
      }
    </style>
  </head>
  
  <body>

    ";
        // line 55
        $this->displayBlock('header', $context, $blocks);
        // line 58
        echo "    
    <main id=\"MainContent\" role=\"main\">
      ";
        // line 60
        $this->displayBlock('content', $context, $blocks);
        // line 63
        echo "    </main>

    ";
        // line 65
        $this->displayBlock('footer', $context, $blocks);
        // line 68
        echo "
    ";
        // line 69
        $this->displayBlock('scripts', $context, $blocks);
        // line 71
        echo "    
    <script type='text/javascript' src='/public/js/global/global.min.js?ver=5.7'></script>
    <script type='text/javascript' src='/public/js/theme.js?ver=5.7'></script>
    <script>
      window.onload = function() {
        themePaginationScroll()
      };
    </script>
  </body>
</html>";
    }

    // line 13
    public function block_styles($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 14
        echo "    ";
    }

    // line 55
    public function block_header($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 56
        echo "      ";
        $this->loadTemplate("header.twig", "base.twig", 56)->display($context);
        // line 57
        echo "    ";
    }

    // line 60
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 61
        echo "        Sorry, Nothing to Display!!
      ";
    }

    // line 65
    public function block_footer($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 66
        echo "      ";
        $this->loadTemplate("footer.twig", "base.twig", 66)->display($context);
        // line 67
        echo "    ";
    }

    // line 69
    public function block_scripts($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 70
        echo "    ";
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
        return array (  180 => 70,  176 => 69,  172 => 67,  169 => 66,  165 => 65,  160 => 61,  156 => 60,  152 => 57,  149 => 56,  145 => 55,  141 => 14,  137 => 13,  124 => 71,  122 => 69,  119 => 68,  117 => 65,  113 => 63,  111 => 60,  107 => 58,  105 => 55,  63 => 15,  61 => 13,  50 => 7,  42 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<!DOCTYPE html>
<html lang=\"en\">

  <head>
    <meta charset=\"utf-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
    <title>{{ site.title }} | {{site.tagline}}</title>
    <link rel=\"preconnect\" href=\"https://fonts.gstatic.com\">
    <link href=\"https://fonts.googleapis.com/css2?family=PT+Serif&family=Poppins:wght@400;700&display=swap\" rel=\"stylesheet\">
    <link rel=\"stylesheet\" type=\"text/css\" href=\"/public/css/base.css\">
    <link rel=\"stylesheet\" type=\"text/css\" href=\"/public/css/global.css\">
    <script src=\"/public/js/global/base.min.js\"></script>
    {% block styles %}
    {% endblock %}
    <style>
      /* font stylings */
      html,
      .pt-serif {
        font-family: 'PT Serif', serif;
      }
      .uk-h1, .uk-h2, .uk-h3, .uk-h4, .uk-h5, .uk-h6, 
      .uk-heading-2xlarge, .uk-heading-large, .uk-heading-medium, .uk-heading-small, .uk-heading-xlarge, h1, h2, h3, h4, h5, h6,
      .uk-logo {
        font-family: 'Poppins', sans-serif;
        letter-spacing: -1px;
        font-weight: 700;
        word-spacing: 2px;
      }
      .poppins, 
      .site-tagline, 
      .uk-article-meta, 
      .uk-label, 
      .uk-navbar-item, .uk-navbar-nav>li>a, .uk-navbar-toggle,
      .uk-button {
        font-family: 'Poppins', sans-serif;
      }
      .uk-button {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        letter-spacing: -1px;
        word-spacing: 1px;
      }
      /* reset bold elements */
      .uk-text-lead {
        font-size: 1.3rem;
      }
      .uk-light .uk-text-lead {
        color: #fff;
      }
    </style>
  </head>
  
  <body>

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

    {% block scripts %}
    {% endblock %}
    
    <script type='text/javascript' src='/public/js/global/global.min.js?ver=5.7'></script>
    <script type='text/javascript' src='/public/js/theme.js?ver=5.7'></script>
    <script>
      window.onload = function() {
        themePaginationScroll()
      };
    </script>
  </body>
</html>", "base.twig", "C:\\xampp\\htdocs\\static.com\\app\\views\\base.twig");
    }
}
