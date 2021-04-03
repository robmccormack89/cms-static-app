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

/* 404.twig */
class __TwigTemplate_9f201a3c17ef40eabc24cef2fe04fed1ca40cf30bbecc0cf8c01b8048f8079b2 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
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
        $this->parent = $this->loadTemplate("base.twig", "404.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 4
        echo "
  <div id=\"Single265\" class=\"page-265 page uk-section uk-container\" data-template=\"page.twig\">
    <div class=\"uk-grid-large uk-grid\" uk-grid=\"\">

      <section class=\"left-section uk-width-2-3@m uk-first-column\">
        <article class=\"uk-article\">

          <header class=\"article-header\">
            <h1 class=\"uk-article-title\">Nothing here..</h1>
          </header>

          <div class=\"page-content uk-margin-medium-bottom\">
            <p class=\"\">Oops, there is nothing to be found here.</p>
            <a href=\"";
        // line 17
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["site"] ?? null), "baseurl", [], "any", false, false, false, 17), "html", null, true);
        echo "\" class=\"uk-button uk-button-small uk-button-default uk-margin-top\">Back to Homepage</a>
          </div>

        </article>
      </section>

      ";
        // line 23
        $this->loadTemplate("sidebar.twig", "404.twig", 23)->display($context);
        // line 24
        echo "
    </div>

  </div>



";
    }

    public function getTemplateName()
    {
        return "404.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  76 => 24,  74 => 23,  65 => 17,  50 => 4,  46 => 3,  35 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends \"base.twig\" %}

{% block content %}

  <div id=\"Single265\" class=\"page-265 page uk-section uk-container\" data-template=\"page.twig\">
    <div class=\"uk-grid-large uk-grid\" uk-grid=\"\">

      <section class=\"left-section uk-width-2-3@m uk-first-column\">
        <article class=\"uk-article\">

          <header class=\"article-header\">
            <h1 class=\"uk-article-title\">Nothing here..</h1>
          </header>

          <div class=\"page-content uk-margin-medium-bottom\">
            <p class=\"\">Oops, there is nothing to be found here.</p>
            <a href=\"{{site.baseurl}}\" class=\"uk-button uk-button-small uk-button-default uk-margin-top\">Back to Homepage</a>
          </div>

        </article>
      </section>

      {% include 'sidebar.twig' %}

    </div>

  </div>



{% endblock %}

", "404.twig", "C:\\xampp\\htdocs\\static.com\\app\\views\\404.twig");
    }
}
