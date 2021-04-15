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
class __TwigTemplate_63697d4c97d4fe98a8dfbc83448441f7305870131faa3a949abb7c1c5bd0ed4f extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'page_content' => [$this, 'block_page_content'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "page.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $this->parent = $this->loadTemplate("page.twig", "page-test.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_page_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 4
        echo "
  <h3>Page Data</h3>

  <ul>
    <li>Page Title: ";
        // line 8
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "title", [], "any", false, false, false, 8), "html", null, true);
        echo "</li>
    <li>Page Slug: ";
        // line 9
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "slug", [], "any", false, false, false, 9), "html", null, true);
        echo "</li>
    <li>Page Status: ";
        // line 10
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "status", [], "any", false, false, false, 10), "html", null, true);
        echo "</li>
    <li>Page Author: ";
        // line 11
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "author", [], "any", false, false, false, 11), "html", null, true);
        echo "</li>
    <li>Page Date/Time: ";
        // line 12
        echo twig_escape_filter($this->env, twig_date_format_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "date_time", [], "any", false, false, false, 12), "m/d/Y"), "html", null, true);
        echo "</li>
    <li>Page Type: ";
        // line 13
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "type", [], "any", false, false, false, 13), "html", null, true);
        echo "</li>
    <li>Page Content Template: ";
        // line 14
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "content", [], "any", false, false, false, 14), "html", null, true);
        echo "</li>
    <li>Page Excerpt/Preview: ";
        // line 15
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "excerpt", [], "any", false, false, false, 15), "html", null, true);
        echo "</li>
  </ul>
    
  <hr>

  <h3>Page Featured Image Data</h3>

  <ul>
    <li>Featured Image Src: ";
        // line 23
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "featured_img", [], "any", false, false, false, 23), "src", [], "any", false, false, false, 23), "html", null, true);
        echo "</li>
    <li>Featured Image Title: ";
        // line 24
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "featured_img", [], "any", false, false, false, 24), "title", [], "any", false, false, false, 24), "html", null, true);
        echo "</li>
    <li>Featured Image Alt: ";
        // line 25
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "featured_img", [], "any", false, false, false, 25), "alt", [], "any", false, false, false, 25), "html", null, true);
        echo "</li>
    <li>Featured Image Caption: ";
        // line 26
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "featured_img", [], "any", false, false, false, 26), "caption", [], "any", false, false, false, 26), "html", null, true);
        echo "</li>
    <li>Featured Image Description: ";
        // line 27
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "featured_img", [], "any", false, false, false, 27), "description", [], "any", false, false, false, 27), "html", null, true);
        echo "</li>
  </ul>

  <hr>

  <h3>Page Gallery Data</h3>

  ";
        // line 34
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "gallery", [], "any", false, false, false, 34));
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
        foreach ($context['_seq'] as $context["_key"] => $context["img"]) {
            // line 35
            echo "    Image: ";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["loop"], "index", [], "any", false, false, false, 35), "html", null, true);
            echo "
    <ul>
      <li>";
            // line 37
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["img"], "src", [], "any", false, false, false, 37), "html", null, true);
            echo "</li>
      <li>";
            // line 38
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["img"], "title", [], "any", false, false, false, 38), "html", null, true);
            echo "</li>
      <li>";
            // line 39
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["img"], "alt", [], "any", false, false, false, 39), "html", null, true);
            echo "</li>
      <li>";
            // line 40
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["img"], "caption", [], "any", false, false, false, 40), "html", null, true);
            echo "</li>
      <li>";
            // line 41
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["img"], "description", [], "any", false, false, false, 41), "html", null, true);
            echo "</li>
    </ul>
    <hr>
  ";
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
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['img'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 45
        echo "
";
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
        return array (  178 => 45,  160 => 41,  156 => 40,  152 => 39,  148 => 38,  144 => 37,  138 => 35,  121 => 34,  111 => 27,  107 => 26,  103 => 25,  99 => 24,  95 => 23,  84 => 15,  80 => 14,  76 => 13,  72 => 12,  68 => 11,  64 => 10,  60 => 9,  56 => 8,  50 => 4,  46 => 3,  35 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends \"page.twig\" %}

{% block page_content %}

  <h3>Page Data</h3>

  <ul>
    <li>Page Title: {{page.title}}</li>
    <li>Page Slug: {{page.slug}}</li>
    <li>Page Status: {{page.status}}</li>
    <li>Page Author: {{page.author}}</li>
    <li>Page Date/Time: {{page.date_time|date(\"m/d/Y\")}}</li>
    <li>Page Type: {{page.type}}</li>
    <li>Page Content Template: {{page.content}}</li>
    <li>Page Excerpt/Preview: {{page.excerpt}}</li>
  </ul>
    
  <hr>

  <h3>Page Featured Image Data</h3>

  <ul>
    <li>Featured Image Src: {{page.featured_img.src}}</li>
    <li>Featured Image Title: {{page.featured_img.title}}</li>
    <li>Featured Image Alt: {{page.featured_img.alt}}</li>
    <li>Featured Image Caption: {{page.featured_img.caption}}</li>
    <li>Featured Image Description: {{page.featured_img.description}}</li>
  </ul>

  <hr>

  <h3>Page Gallery Data</h3>

  {% for img in page.gallery %}
    Image: {{loop.index}}
    <ul>
      <li>{{img.src}}</li>
      <li>{{img.title}}</li>
      <li>{{img.alt}}</li>
      <li>{{img.caption}}</li>
      <li>{{img.description}}</li>
    </ul>
    <hr>
  {% endfor %}

{% endblock %}", "page-test.twig", "C:\\xampp\\htdocs\\static.com\\app\\views\\pages\\page-test.twig");
    }
}
