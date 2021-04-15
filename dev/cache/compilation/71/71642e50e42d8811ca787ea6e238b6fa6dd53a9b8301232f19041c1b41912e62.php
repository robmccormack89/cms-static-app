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

/* page-test.twig */
class __TwigTemplate_51eac55bf054f0908df41746244bbae20ffb9d776a232ef4a70ac4cb41630672 extends Template
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
        $this->parent = $this->loadTemplate("base.twig", "page-test.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 4
        echo "page test

  <div id=\"Single";
        // line 6
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["single"] ?? null), "id", [], "any", false, false, false, 6), "html", null, true);
        echo "\" class=\"page-";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["single"] ?? null), "id", [], "any", false, false, false, 6), "html", null, true);
        echo " page uk-section uk-container\" data-template=\"single.twig\">
    <div class=\"uk-grid-large uk-grid\" uk-grid=\"\">

      <section class=\"left-section uk-width-2-3@m uk-first-column\">
        <article class=\"uk-article\">

          <header class=\"article-header\">
            <h1 class=\"uk-article-title\">";
        // line 13
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["single"] ?? null), "title", [], "any", false, false, false, 13), "html", null, true);
        echo "</h1>
          </header>
          
          ";
        // line 16
        if ((0 === twig_compare(twig_get_attribute($this->env, $this->source, ($context["single"] ?? null), "status", [], "any", false, false, false, 16), "draft"))) {
            // line 17
            echo "            <p>
              <span class=\"uk-label uk-label-warning\">This page is in Draft mode</span>
            </p>
          ";
        }
        // line 21
        echo "          
          ";
        // line 22
        $this->displayBlock('page_content', $context, $blocks);
        // line 29
        echo "
        </article>
      </section>

      ";
        // line 33
        $this->loadTemplate("sidebar.twig", "page-test.twig", 33)->display($context);
        // line 34
        echo "
    </div>

  </div>
";
    }

    // line 22
    public function block_page_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 23
        echo "            ";
        if (twig_get_attribute($this->env, $this->source, ($context["single"] ?? null), "content", [], "any", false, false, false, 23)) {
            // line 24
            echo "              ";
            $this->loadTemplate(twig_get_attribute($this->env, $this->source, ($context["single"] ?? null), "content", [], "any", false, false, false, 24), "page-test.twig", 24)->display($context);
            // line 25
            echo "            ";
        } else {
            // line 26
            echo "              Nothing to see here. Yet...
            ";
        }
        // line 28
        echo "          ";
    }

    public function getTemplateName()
    {
        return "page-test.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  119 => 28,  115 => 26,  112 => 25,  109 => 24,  106 => 23,  102 => 22,  94 => 34,  92 => 33,  86 => 29,  84 => 22,  81 => 21,  75 => 17,  73 => 16,  67 => 13,  55 => 6,  51 => 4,  47 => 3,  36 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends \"base.twig\" %}

{% block content %}
page test

  <div id=\"Single{{single.id}}\" class=\"page-{{single.id}} page uk-section uk-container\" data-template=\"single.twig\">
    <div class=\"uk-grid-large uk-grid\" uk-grid=\"\">

      <section class=\"left-section uk-width-2-3@m uk-first-column\">
        <article class=\"uk-article\">

          <header class=\"article-header\">
            <h1 class=\"uk-article-title\">{{single.title}}</h1>
          </header>
          
          {% if single.status == 'draft' %}
            <p>
              <span class=\"uk-label uk-label-warning\">This page is in Draft mode</span>
            </p>
          {% endif %}
          
          {% block page_content %}
            {% if single.content %}
              {% include single.content %}
            {% else %}
              Nothing to see here. Yet...
            {% endif %}
          {% endblock %}

        </article>
      </section>

      {% include 'sidebar.twig' %}

    </div>

  </div>
{% endblock %}

", "page-test.twig", "C:\\xampp\\htdocs\\static.com\\app\\views\\single\\page-test.twig");
    }
}
