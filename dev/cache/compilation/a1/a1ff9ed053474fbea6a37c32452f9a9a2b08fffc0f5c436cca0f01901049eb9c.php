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

/* tease.twig */
class __TwigTemplate_d9212dfc725f4ff7a076c4028edda61541836d0b0dd0653125b8914f7ab4d739 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<article id=\"post-";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["post"] ?? null), "id", [], "any", false, false, false, 1), "html", null, true);
        echo "\" class=\"uk-section uk-section-small uk-padding-remove-top item post-";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["post"] ?? null), "id", [], "any", false, false, false, 1), "html", null, true);
        echo " type-";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["post"] ?? null), "type", [], "any", false, false, false, 1), "html", null, true);
        echo "\">
  <header>
    <figure class=\"uk-position-relatve uk-inline uk-margin-remove\">
      <a class=\"uk-link-reset\" href=\"";
        // line 4
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["post"] ?? null), "link", [], "any", false, false, false, 4), "html", null, true);
        echo "\">
        <img class=\"\" data-src=\"";
        // line 5
        echo twig_escape_filter($this->env, ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["post"] ?? null), "featured_img", [], "any", false, true, false, 5), "src", [], "any", true, true, false, 5)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["post"] ?? null), "featured_img", [], "any", false, true, false, 5), "src", [], "any", false, false, false, 5), twig_get_attribute($this->env, $this->source, ($context["configs"] ?? null), "placeholder_img_src", [], "any", false, false, false, 5))) : (twig_get_attribute($this->env, $this->source, ($context["configs"] ?? null), "placeholder_img_src", [], "any", false, false, false, 5))), "html", null, true);
        echo "\" width=\"776\" height=\"400\" alt=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["post"] ?? null), "featured_img", [], "any", false, false, false, 5), "alt", [], "any", false, false, false, 5), "html", null, true);
        echo "\" uk-img>
      </a>
    </figure>
    <h2 class=\"uk-text-bold uk-margin-top\">
      <a class=\"uk-link-reset\" href=\"";
        // line 9
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["post"] ?? null), "link", [], "any", false, false, false, 9), "html", null, true);
        echo "\">
        ";
        // line 10
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["post"] ?? null), "title", [], "any", false, false, false, 10), "html", null, true);
        echo "
      </a>
    </h2>
    <p class=\"uk-article-meta\">
      ";
        // line 14
        echo twig_escape_filter($this->env, twig_date_format_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["post"] ?? null), "date", [], "any", false, false, false, 14), "F j, Y"), "html", null, true);
        echo "
    </p>
  </header>
  <p>";
        // line 17
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["post"] ?? null), "excerpt", [], "any", false, false, false, 17), "html", null, true);
        echo "</p>
  <a href=\"";
        // line 18
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["post"] ?? null), "link", [], "any", false, false, false, 18), "html", null, true);
        echo "\" class=\"\">Read more</a>
</article>";
    }

    public function getTemplateName()
    {
        return "tease.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  82 => 18,  78 => 17,  72 => 14,  65 => 10,  61 => 9,  52 => 5,  48 => 4,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<article id=\"post-{{post.id}}\" class=\"uk-section uk-section-small uk-padding-remove-top item post-{{post.id}} type-{{post.type}}\">
  <header>
    <figure class=\"uk-position-relatve uk-inline uk-margin-remove\">
      <a class=\"uk-link-reset\" href=\"{{post.link}}\">
        <img class=\"\" data-src=\"{{post.featured_img.src|default(configs.placeholder_img_src)}}\" width=\"776\" height=\"400\" alt=\"{{post.featured_img.alt}}\" uk-img>
      </a>
    </figure>
    <h2 class=\"uk-text-bold uk-margin-top\">
      <a class=\"uk-link-reset\" href=\"{{post.link}}\">
        {{post.title}}
      </a>
    </h2>
    <p class=\"uk-article-meta\">
      {{post.date|date(\"F j, Y\")}}
    </p>
  </header>
  <p>{{post.excerpt}}</p>
  <a href=\"{{post.link}}\" class=\"\">Read more</a>
</article>", "tease.twig", "C:\\xampp\\htdocs\\static.com\\app\\views\\parts\\tease.twig");
    }
}
