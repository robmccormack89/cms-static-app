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

/* home.twig */
class __TwigTemplate_c927ab9a121b796df4835f632e7d9beb314caf18377010c3124bedbbb8108400 extends Template
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
        $this->parent = $this->loadTemplate("base.twig", "home.twig", 1);
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
        echo "
<div data-template=\"homepage.twig\">

  <section class=\"home-more uk-section uk-section-small home-posts-section\">
    <div class=\"uk-container\">
      Homepage
    </div>
  </section>

</div>

";
    }

    public function getTemplateName()
    {
        return "home.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  92 => 16,  88 => 15,  82 => 12,  78 => 11,  73 => 10,  69 => 9,  65 => 8,  60 => 6,  56 => 5,  51 => 4,  47 => 3,  36 => 1,);
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

<div data-template=\"homepage.twig\">

  <section class=\"home-more uk-section uk-section-small home-posts-section\">
    <div class=\"uk-container\">
      Homepage
    </div>
  </section>

</div>

{% endblock %}", "home.twig", "C:\\xampp\\htdocs\\static.com\\app\\views\\home.twig");
    }
}
