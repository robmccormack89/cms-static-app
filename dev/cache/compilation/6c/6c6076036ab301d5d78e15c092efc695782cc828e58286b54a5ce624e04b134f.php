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

/* single.twig */
class __TwigTemplate_09d57db462d6eb85d34e421318590314668b272c9c5d2dca44edade7140b4af0 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'seo_meta' => [$this, 'block_seo_meta'],
            'content' => [$this, 'block_content'],
            'page_content' => [$this, 'block_page_content'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "base.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $this->parent = $this->loadTemplate("base.twig", "single.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_seo_meta($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 4
        echo "  <title>";
        echo twig_escape_filter($this->env, ((twig_get_attribute($this->env, $this->source, ($context["single"] ?? null), "meta_title", [], "any", true, true, false, 4)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["single"] ?? null), "meta_title", [], "any", false, false, false, 4), ((twig_get_attribute($this->env, $this->source, ($context["single"] ?? null), "title", [], "any", false, false, false, 4) . " | ") . twig_get_attribute($this->env, $this->source, ($context["configs"] ?? null), "site_title", [], "any", false, false, false, 4)))) : (((twig_get_attribute($this->env, $this->source, ($context["single"] ?? null), "title", [], "any", false, false, false, 4) . " | ") . twig_get_attribute($this->env, $this->source, ($context["configs"] ?? null), "site_title", [], "any", false, false, false, 4)))), "html", null, true);
        echo "</title>
  <meta name=\"description\" content=\"";
        // line 5
        echo twig_escape_filter($this->env, ((twig_get_attribute($this->env, $this->source, ($context["single"] ?? null), "meta_description", [], "any", true, true, false, 5)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["single"] ?? null), "meta_description", [], "any", false, false, false, 5), twig_get_attribute($this->env, $this->source, ($context["single"] ?? null), "excerpt", [], "any", false, false, false, 5))) : (twig_get_attribute($this->env, $this->source, ($context["single"] ?? null), "excerpt", [], "any", false, false, false, 5))), "html", null, true);
        echo "\">
  <link rel=\"canonical\" href=\"";
        // line 6
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["configs"] ?? null), "current_url", [], "any", false, false, false, 6), "html", null, true);
        echo "\" />
  <meta property=\"og:type\" content=\"article\" />
  <meta property=\"og:title\" content=\"";
        // line 8
        echo twig_escape_filter($this->env, ((twig_get_attribute($this->env, $this->source, ($context["single"] ?? null), "meta_title", [], "any", true, true, false, 8)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["single"] ?? null), "meta_title", [], "any", false, false, false, 8), ((twig_get_attribute($this->env, $this->source, ($context["single"] ?? null), "title", [], "any", false, false, false, 8) . " | ") . twig_get_attribute($this->env, $this->source, ($context["configs"] ?? null), "site_title", [], "any", false, false, false, 8)))) : (((twig_get_attribute($this->env, $this->source, ($context["single"] ?? null), "title", [], "any", false, false, false, 8) . " | ") . twig_get_attribute($this->env, $this->source, ($context["configs"] ?? null), "site_title", [], "any", false, false, false, 8)))), "html", null, true);
        echo "\" />
  <meta property=\"og:description\" content=\"";
        // line 9
        echo twig_escape_filter($this->env, ((twig_get_attribute($this->env, $this->source, ($context["single"] ?? null), "meta_description", [], "any", true, true, false, 9)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["single"] ?? null), "meta_description", [], "any", false, false, false, 9), twig_get_attribute($this->env, $this->source, ($context["single"] ?? null), "excerpt", [], "any", false, false, false, 9))) : (twig_get_attribute($this->env, $this->source, ($context["single"] ?? null), "excerpt", [], "any", false, false, false, 9))), "html", null, true);
        echo "\" />
  <meta property=\"og:image\" content=\"";
        // line 10
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["configs"] ?? null), "base_url", [], "any", false, false, false, 10), "html", null, true);
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["single"] ?? null), "featured_img", [], "any", false, false, false, 10), "src", [], "any", false, false, false, 10), "html", null, true);
        echo "\" />
  <meta property=\"og:url\" content=\"";
        // line 11
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["configs"] ?? null), "current_url", [], "any", false, false, false, 11), "html", null, true);
        echo "\" />
  <meta property=\"og:site_name\" content=\"";
        // line 12
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["configs"] ?? null), "site_title", [], "any", false, false, false, 12), "html", null, true);
        echo "\" />
";
    }

    // line 15
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 16
        echo "  <div id=\"Single";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["single"] ?? null), "id", [], "any", false, false, false, 16), "html", null, true);
        echo "\" class=\"page-";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["single"] ?? null), "id", [], "any", false, false, false, 16), "html", null, true);
        echo " page uk-section uk-container\" data-template=\"single.twig\">
    <div class=\"uk-grid-large uk-grid\" uk-grid=\"\">

      <section class=\"left-section uk-width-2-3@m uk-first-column\">
        <article class=\"uk-article\">

          <header class=\"article-header\">
            <h1 class=\"uk-article-title uk-text-bold\">";
        // line 23
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["single"] ?? null), "title", [], "any", false, false, false, 23), "html", null, true);
        echo "</h1>
          </header>
          
          ";
        // line 26
        if ((0 === twig_compare(twig_get_attribute($this->env, $this->source, ($context["single"] ?? null), "status", [], "any", false, false, false, 26), "draft"))) {
            // line 27
            echo "            <p class=\"uk-margin-bottom\">
              <span class=\"uk-label uk-label-warning\">This page is in Draft mode</span>
            </p>
          ";
        }
        // line 31
        echo "          
          ";
        // line 32
        $this->displayBlock('page_content', $context, $blocks);
        // line 35
        echo "
        </article>
      </section>

      ";
        // line 39
        $this->loadTemplate("sidebar.twig", "single.twig", 39)->display($context);
        // line 40
        echo "
    </div>
  </div>
";
    }

    // line 32
    public function block_page_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 33
        echo "            Nothing to see here. Yet...
          ";
    }

    public function getTemplateName()
    {
        return "single.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  144 => 33,  140 => 32,  133 => 40,  131 => 39,  125 => 35,  123 => 32,  120 => 31,  114 => 27,  112 => 26,  106 => 23,  93 => 16,  89 => 15,  83 => 12,  79 => 11,  74 => 10,  70 => 9,  66 => 8,  61 => 6,  57 => 5,  52 => 4,  48 => 3,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends \"base.twig\" %}

{% block seo_meta %}
  <title>{{single.meta_title|default(single.title ~ ' | ' ~ configs.site_title)}}</title>
  <meta name=\"description\" content=\"{{single.meta_description|default(single.excerpt)}}\">
  <link rel=\"canonical\" href=\"{{configs.current_url}}\" />
  <meta property=\"og:type\" content=\"article\" />
  <meta property=\"og:title\" content=\"{{single.meta_title|default(single.title ~ ' | ' ~ configs.site_title)}}\" />
  <meta property=\"og:description\" content=\"{{single.meta_description|default(single.excerpt)}}\" />
  <meta property=\"og:image\" content=\"{{configs.base_url}}{{single.featured_img.src}}\" />
  <meta property=\"og:url\" content=\"{{configs.current_url}}\" />
  <meta property=\"og:site_name\" content=\"{{configs.site_title}}\" />
{% endblock %}

{% block content %}
  <div id=\"Single{{single.id}}\" class=\"page-{{single.id}} page uk-section uk-container\" data-template=\"single.twig\">
    <div class=\"uk-grid-large uk-grid\" uk-grid=\"\">

      <section class=\"left-section uk-width-2-3@m uk-first-column\">
        <article class=\"uk-article\">

          <header class=\"article-header\">
            <h1 class=\"uk-article-title uk-text-bold\">{{single.title}}</h1>
          </header>
          
          {% if single.status == 'draft' %}
            <p class=\"uk-margin-bottom\">
              <span class=\"uk-label uk-label-warning\">This page is in Draft mode</span>
            </p>
          {% endif %}
          
          {% block page_content %}
            Nothing to see here. Yet...
          {% endblock %}

        </article>
      </section>

      {% include 'sidebar.twig' %}

    </div>
  </div>
{% endblock %}

", "single.twig", "C:\\xampp\\htdocs\\static.com\\app\\views\\single\\single.twig");
    }
}
