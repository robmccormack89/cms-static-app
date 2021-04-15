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

/* page-about.twig.php */
class __TwigTemplate_e87b169253b947856211191aa42f9534fc7c8bccdae0ca0317e6167bcbff8487 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
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
        $this->parent = $this->loadTemplate("base.twig", "page-about.twig.php", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 4
        echo "  <div id=\"Single";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "id", [], "any", false, false, false, 4), "html", null, true);
        echo "\" class=\"page-";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "id", [], "any", false, false, false, 4), "html", null, true);
        echo " page uk-section uk-container\" data-template=\"page.twig\">
    <div class=\"uk-grid-large uk-grid\" uk-grid=\"\">

      <section class=\"left-section uk-width-2-3@m uk-first-column\">
        <article class=\"uk-article\">

          <header class=\"article-header\">
            <h1 class=\"uk-article-title\">";
        // line 11
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "title", [], "any", false, false, false, 11), "html", null, true);
        echo "</h1>
          </header>
          
          ";
        // line 14
        if ((0 === twig_compare(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "status", [], "any", false, false, false, 14), "draft"))) {
            // line 15
            echo "            <p>
              <span class=\"uk-label uk-label-warning\">This page is in Draft mode</span>
            </p>
          ";
        }
        // line 19
        echo "          
          ";
        // line 20
        $this->displayBlock('page_content', $context, $blocks);
        // line 29
        echo "
        </article>
      </section>

      ";
        // line 33
        $this->loadTemplate("sidebar.twig", "page-about.twig.php", 33)->display($context);
        // line 34
        echo "
    </div>

  </div>
";
    }

    // line 20
    public function block_page_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 21
        echo "          
            ";
        // line 22
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["movies"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["movie"]) {
            // line 23
            echo "              ";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["movie"], "title", [], "any", false, false, false, 23), "html", null, true);
            echo "
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['movie'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 25
        echo "            
            ";
        // line 26
        echo twig_escape_filter($this->env, ($context["app_protocol"] ?? null), "html", null, true);
        echo "
            
          ";
    }

    public function getTemplateName()
    {
        return "page-about.twig.php";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  122 => 26,  119 => 25,  110 => 23,  106 => 22,  103 => 21,  99 => 20,  91 => 34,  89 => 33,  83 => 29,  81 => 20,  78 => 19,  72 => 15,  70 => 14,  64 => 11,  51 => 4,  47 => 3,  36 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends \"base.twig\" %}

{% block content %}
  <div id=\"Single{{page.id}}\" class=\"page-{{page.id}} page uk-section uk-container\" data-template=\"page.twig\">
    <div class=\"uk-grid-large uk-grid\" uk-grid=\"\">

      <section class=\"left-section uk-width-2-3@m uk-first-column\">
        <article class=\"uk-article\">

          <header class=\"article-header\">
            <h1 class=\"uk-article-title\">{{page.title}}</h1>
          </header>
          
          {% if page.status == 'draft' %}
            <p>
              <span class=\"uk-label uk-label-warning\">This page is in Draft mode</span>
            </p>
          {% endif %}
          
          {% block page_content %}
          
            {% for movie in movies %}
              {{movie.title}}
            {% endfor %}
            
            {{app_protocol}}
            
          {% endblock %}

        </article>
      </section>

      {% include 'sidebar.twig' %}

    </div>

  </div>
{% endblock %}

", "page-about.twig.php", "C:\\xampp\\htdocs\\static.com\\app\\views\\pages\\page-about.twig.php");
    }
}
