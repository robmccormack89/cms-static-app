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

/* header.twig */
class __TwigTemplate_ad819aabdf8cb41d605d2888c2197047a80923bec281933a59215e017be03f91 extends Template
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
        echo "<style>
  /* header */
  .site-header .uk-navbar-nav>li>a {
    text-transform: capitalize;
  }
  .site-header .uk-navbar-item, .site-header .uk-navbar-nav>li>a, .site-header .uk-navbar-toggle {
    font-size: 1rem;
  }
</style>
<header id=\"SiteHeader\" class=\"site-header uk-background-default uk-padding-small uk-padding-remove-horizontal\">
  <nav class=\"uk-navbar-transparent uk-container uk-navbar\" uk-navbar>
    <div class=\"header-logo uk-navbar-left nav-logo-area\">
      <div class=\"\">
        <a class=\"site-logo uk-logo uk-padding-remove uk-text-bold uk-text-emphasis line-height-1\" href=\"/\">
          ";
        // line 15
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["configs"] ?? null), "site_title", [], "any", false, false, false, 15), "html", null, true);
        echo "
        </a>
        <div>
          <p class=\"site-tagline uk-text-small uk-margin-remove line-height-1\">";
        // line 18
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["configs"] ?? null), "site_description", [], "any", false, false, false, 18), "html", null, true);
        echo "</p>
        </div>
      </div>
    </div>
    <div class=\"header-main-nav uk-navbar-center nav-overlay\">
      
      <ul class=\"main-menu-nav uk-navbar-nav uk-visible@m\">
        ";
        // line 25
        if (($context["main_menu"] ?? null)) {
            // line 26
            echo "          ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["main_menu"] ?? null), "menu_items", [], "any", false, false, false, 26));
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 27
                echo "            <li class=\"menu-item\">
              <a href=\"";
                // line 28
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["item"], "link", [], "any", false, false, false, 28), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["item"], "title", [], "any", false, false, false, 28), "html", null, true);
                echo "</a>
                ";
                // line 29
                if (twig_get_attribute($this->env, $this->source, $context["item"], "children", [], "any", false, false, false, 29)) {
                    // line 30
                    echo "                <div class=\"uk-navbar-dropdown\">
                  <ul class=\"uk-nav uk-navbar-dropdown-nav\">
                    ";
                    // line 32
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["item"], "children", [], "any", false, false, false, 32));
                    foreach ($context['_seq'] as $context["_key"] => $context["child_item"]) {
                        // line 33
                        echo "                      <li><a href=\"";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["child_item"], "link", [], "any", false, false, false, 33), "html", null, true);
                        echo "\">";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["child_item"], "title", [], "any", false, false, false, 33), "html", null, true);
                        echo "</a></li>
                    ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['child_item'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 34
                    echo "  
                  </ul>
                </div>
                ";
                }
                // line 38
                echo "            </li>
          ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 39
            echo "  
        ";
        } else {
            // line 41
            echo "          <li class=\"menu-item\">Add a menu with the slug main-menu to menus.json</li>
        ";
        }
        // line 43
        echo "      </ul>
      
    </div>
    <div class=\"header-icons-nav uk-navbar-right\">
      <ul class=\"mobile-menu-toggle uk-navbar-nav\">
        <li>
          <a class=\"uk-navbar-toggle\" href=\"#\">
            <i class=\"fas fa-bars\"></i>
          </a>
        </li>
      </ul>
      <ul class=\"dark-light-switcher pull-right-15 uk-navbar-nav\">
        <li>
          <a id=\"DarkSwitch\" class=\"uk-navbar-toggle darklight-switch\" ";
        // line 56
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["dark_light_def"] ?? null), "moon_link_show_hide", [], "any", false, false, false, 56), "html", null, true);
        echo ">
            <i class=\"far fa-moon\"></i>
          </a>
        </li>
        <li>
          <a id=\"LightSwitch\" class=\"uk-navbar-toggle darklight-switch\" ";
        // line 61
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["dark_light_def"] ?? null), "sun_link_show_hide", [], "any", false, false, false, 61), "html", null, true);
        echo ">
            <i class=\"fas fa-sun\"></i>
          </a>
        </li>
      </ul>
    </div>
  </nav>
</header>";
    }

    public function getTemplateName()
    {
        return "header.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  150 => 61,  142 => 56,  127 => 43,  123 => 41,  119 => 39,  112 => 38,  106 => 34,  95 => 33,  91 => 32,  87 => 30,  85 => 29,  79 => 28,  76 => 27,  71 => 26,  69 => 25,  59 => 18,  53 => 15,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<style>
  /* header */
  .site-header .uk-navbar-nav>li>a {
    text-transform: capitalize;
  }
  .site-header .uk-navbar-item, .site-header .uk-navbar-nav>li>a, .site-header .uk-navbar-toggle {
    font-size: 1rem;
  }
</style>
<header id=\"SiteHeader\" class=\"site-header uk-background-default uk-padding-small uk-padding-remove-horizontal\">
  <nav class=\"uk-navbar-transparent uk-container uk-navbar\" uk-navbar>
    <div class=\"header-logo uk-navbar-left nav-logo-area\">
      <div class=\"\">
        <a class=\"site-logo uk-logo uk-padding-remove uk-text-bold uk-text-emphasis line-height-1\" href=\"/\">
          {{configs.site_title}}
        </a>
        <div>
          <p class=\"site-tagline uk-text-small uk-margin-remove line-height-1\">{{configs.site_description}}</p>
        </div>
      </div>
    </div>
    <div class=\"header-main-nav uk-navbar-center nav-overlay\">
      
      <ul class=\"main-menu-nav uk-navbar-nav uk-visible@m\">
        {% if main_menu %}
          {% for item in main_menu.menu_items %}
            <li class=\"menu-item\">
              <a href=\"{{item.link}}\">{{item.title}}</a>
                {% if item.children %}
                <div class=\"uk-navbar-dropdown\">
                  <ul class=\"uk-nav uk-navbar-dropdown-nav\">
                    {% for child_item in item.children %}
                      <li><a href=\"{{child_item.link}}\">{{child_item.title}}</a></li>
                    {% endfor %}  
                  </ul>
                </div>
                {% endif %}
            </li>
          {% endfor %}  
        {% else %}
          <li class=\"menu-item\">Add a menu with the slug main-menu to menus.json</li>
        {% endif %}
      </ul>
      
    </div>
    <div class=\"header-icons-nav uk-navbar-right\">
      <ul class=\"mobile-menu-toggle uk-navbar-nav\">
        <li>
          <a class=\"uk-navbar-toggle\" href=\"#\">
            <i class=\"fas fa-bars\"></i>
          </a>
        </li>
      </ul>
      <ul class=\"dark-light-switcher pull-right-15 uk-navbar-nav\">
        <li>
          <a id=\"DarkSwitch\" class=\"uk-navbar-toggle darklight-switch\" {{dark_light_def.moon_link_show_hide}}>
            <i class=\"far fa-moon\"></i>
          </a>
        </li>
        <li>
          <a id=\"LightSwitch\" class=\"uk-navbar-toggle darklight-switch\" {{dark_light_def.sun_link_show_hide}}>
            <i class=\"fas fa-sun\"></i>
          </a>
        </li>
      </ul>
    </div>
  </nav>
</header>", "header.twig", "C:\\xampp\\htdocs\\static.com\\app\\views\\parts\\header.twig");
    }
}
