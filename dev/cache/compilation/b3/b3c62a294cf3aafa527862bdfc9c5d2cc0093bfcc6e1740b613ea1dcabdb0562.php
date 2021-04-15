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

/* archive.twig */
class __TwigTemplate_628c619a9c822def0cab5344830d4d508d134bdd6d1aca4c568c14ca10eac10b extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'seo_meta' => [$this, 'block_seo_meta'],
            'styles' => [$this, 'block_styles'],
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
        $this->parent = $this->loadTemplate("base.twig", "archive.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_seo_meta($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 4
        echo "  <title>";
        echo twig_escape_filter($this->env, ((twig_get_attribute($this->env, $this->source, ($context["archive"] ?? null), "meta_title", [], "any", true, true, false, 4)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["archive"] ?? null), "meta_title", [], "any", false, false, false, 4), ((twig_get_attribute($this->env, $this->source, ($context["archive"] ?? null), "title", [], "any", false, false, false, 4) . " | ") . twig_get_attribute($this->env, $this->source, ($context["configs"] ?? null), "site_title", [], "any", false, false, false, 4)))) : (((twig_get_attribute($this->env, $this->source, ($context["archive"] ?? null), "title", [], "any", false, false, false, 4) . " | ") . twig_get_attribute($this->env, $this->source, ($context["configs"] ?? null), "site_title", [], "any", false, false, false, 4)))), "html", null, true);
        echo "</title>
  <meta name=\"description\" content=\"";
        // line 5
        echo twig_escape_filter($this->env, ((twig_get_attribute($this->env, $this->source, ($context["archive"] ?? null), "meta_description", [], "any", true, true, false, 5)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["archive"] ?? null), "meta_description", [], "any", false, false, false, 5), twig_get_attribute($this->env, $this->source, ($context["archive"] ?? null), "description", [], "any", false, false, false, 5))) : (twig_get_attribute($this->env, $this->source, ($context["archive"] ?? null), "description", [], "any", false, false, false, 5))), "html", null, true);
        echo "\">
  <link rel=\"canonical\" href=\"";
        // line 6
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["configs"] ?? null), "current_url", [], "any", false, false, false, 6), "html", null, true);
        echo "\" />
  <meta property=\"og:type\" content=\"article\" />
  <meta property=\"og:title\" content=\"";
        // line 8
        echo twig_escape_filter($this->env, ((twig_get_attribute($this->env, $this->source, ($context["archive"] ?? null), "meta_title", [], "any", true, true, false, 8)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["archive"] ?? null), "meta_title", [], "any", false, false, false, 8), ((twig_get_attribute($this->env, $this->source, ($context["archive"] ?? null), "title", [], "any", false, false, false, 8) . " | ") . twig_get_attribute($this->env, $this->source, ($context["configs"] ?? null), "site_title", [], "any", false, false, false, 8)))) : (((twig_get_attribute($this->env, $this->source, ($context["archive"] ?? null), "title", [], "any", false, false, false, 8) . " | ") . twig_get_attribute($this->env, $this->source, ($context["configs"] ?? null), "site_title", [], "any", false, false, false, 8)))), "html", null, true);
        echo "\" />
  <meta property=\"og:description\" content=\"";
        // line 9
        echo twig_escape_filter($this->env, ((twig_get_attribute($this->env, $this->source, ($context["archive"] ?? null), "meta_description", [], "any", true, true, false, 9)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["archive"] ?? null), "meta_description", [], "any", false, false, false, 9), twig_get_attribute($this->env, $this->source, ($context["archive"] ?? null), "description", [], "any", false, false, false, 9))) : (twig_get_attribute($this->env, $this->source, ($context["archive"] ?? null), "description", [], "any", false, false, false, 9))), "html", null, true);
        echo "\" />
  <meta property=\"og:image\" content=\"";
        // line 10
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["configs"] ?? null), "base_url", [], "any", false, false, false, 10), "html", null, true);
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["archive"] ?? null), "featured_img", [], "any", false, false, false, 10), "src", [], "any", false, false, false, 10), "html", null, true);
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
    public function block_styles($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 16
        echo "  <style>
    /* tease */
    .item img {
      max-height: 400px;
      object-fit: cover;
    }
  </style>
";
    }

    // line 25
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 26
        echo "
<div id=\"Archive";
        // line 27
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["archive"] ?? null), "title", [], "any", false, false, false, 27), "html", null, true);
        echo "\" class=\"archive\" data-template=\"archive.twig\">

  <header class=\"uk-section uk-section-muted uk-section-small uk-text-center theme-border-top theme-border-bottom\">
    <div class=\"uk-container uk-container-small\">
      <h1 class=\"uk-article-title uk-text-bold\">";
        // line 31
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["archive"] ?? null), "title", [], "any", false, false, false, 31), "html", null, true);
        echo "</h1>
      <hr class=\"uk-divider-small\">
      <p class=\"uk-text-lead\">";
        // line 33
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["archive"] ?? null), "description", [], "any", false, false, false, 33), "html", null, true);
        echo "</p>
    </div>
  </header>

  <div class=\"uk-container\">
    
    <ul class=\"uk-breadcrumb uk-margin-small-top uk-margin-remove-bottom\">
      <li><a href=\"/\">Home</a></li>
      <li><span>";
        // line 41
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["archive"] ?? null), "title", [], "any", false, false, false, 41), "html", null, true);
        echo "</span></li>
    </ul>
    
    <div class=\"uk-section uk-section-small\">
      <div class=\"uk-grid-large uk-grid\" uk-grid>
        
        <section class=\"left-section uk-width-2-3@m\">
          ";
        // line 48
        $this->displayBlock('page_content', $context, $blocks);
        // line 55
        echo "        </section>
        
        ";
        // line 57
        $this->loadTemplate("sidebar.twig", "archive.twig", 57)->display($context);
        // line 58
        echo "        
      </div>
    </div>
  </div>
</div>
";
    }

    // line 48
    public function block_page_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 49
        echo "            <div class=\"archive-posts\">
              ";
        // line 50
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["archive"] ?? null), "posts", [], "any", false, false, false, 50));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["post"]) {
            // line 51
            echo "                ";
            $this->loadTemplate("tease.twig", "archive.twig", 51)->display($context);
            // line 52
            echo "              ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['post'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 53
        echo "            </div>
          ";
    }

    public function getTemplateName()
    {
        return "archive.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  203 => 53,  189 => 52,  186 => 51,  169 => 50,  166 => 49,  162 => 48,  153 => 58,  151 => 57,  147 => 55,  145 => 48,  135 => 41,  124 => 33,  119 => 31,  112 => 27,  109 => 26,  105 => 25,  94 => 16,  90 => 15,  84 => 12,  80 => 11,  75 => 10,  71 => 9,  67 => 8,  62 => 6,  58 => 5,  53 => 4,  49 => 3,  38 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends \"base.twig\" %}

{% block seo_meta %}
  <title>{{archive.meta_title|default(archive.title ~ ' | ' ~ configs.site_title)}}</title>
  <meta name=\"description\" content=\"{{archive.meta_description|default(archive.description)}}\">
  <link rel=\"canonical\" href=\"{{configs.current_url}}\" />
  <meta property=\"og:type\" content=\"article\" />
  <meta property=\"og:title\" content=\"{{archive.meta_title|default(archive.title ~ ' | ' ~ configs.site_title)}}\" />
  <meta property=\"og:description\" content=\"{{archive.meta_description|default(archive.description)}}\" />
  <meta property=\"og:image\" content=\"{{configs.base_url}}{{archive.featured_img.src}}\" />
  <meta property=\"og:url\" content=\"{{configs.current_url}}\" />
  <meta property=\"og:site_name\" content=\"{{configs.site_title}}\" />
{% endblock %}

{% block styles %}
  <style>
    /* tease */
    .item img {
      max-height: 400px;
      object-fit: cover;
    }
  </style>
{% endblock %}

{% block content %}

<div id=\"Archive{{archive.title}}\" class=\"archive\" data-template=\"archive.twig\">

  <header class=\"uk-section uk-section-muted uk-section-small uk-text-center theme-border-top theme-border-bottom\">
    <div class=\"uk-container uk-container-small\">
      <h1 class=\"uk-article-title uk-text-bold\">{{archive.title}}</h1>
      <hr class=\"uk-divider-small\">
      <p class=\"uk-text-lead\">{{archive.description}}</p>
    </div>
  </header>

  <div class=\"uk-container\">
    
    <ul class=\"uk-breadcrumb uk-margin-small-top uk-margin-remove-bottom\">
      <li><a href=\"/\">Home</a></li>
      <li><span>{{archive.title}}</span></li>
    </ul>
    
    <div class=\"uk-section uk-section-small\">
      <div class=\"uk-grid-large uk-grid\" uk-grid>
        
        <section class=\"left-section uk-width-2-3@m\">
          {% block page_content %}
            <div class=\"archive-posts\">
              {% for post in archive.posts %}
                {% include 'tease.twig' %}
              {% endfor %}
            </div>
          {% endblock %}
        </section>
        
        {% include 'sidebar.twig' %}
        
      </div>
    </div>
  </div>
</div>
{% endblock %}

", "archive.twig", "C:\\xampp\\htdocs\\static.com\\app\\views\\archive\\archive.twig");
    }
}
