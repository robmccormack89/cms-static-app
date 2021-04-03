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
        echo "<header id=\"SiteHeader\" class=\"site-header uk-background-default uk-padding-small uk-padding-remove-horizontal\">

  <nav class=\"uk-navbar-transparent uk-container uk-navbar\" uk-navbar=\"\">

    <div class=\"header-logo uk-navbar-left nav-logo-area\">
      <div class=\"\">
        <a class=\"site-logo uk-logo uk-padding-remove uk-text-bold uk-text-emphasis\" href=\"";
        // line 7
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["site"] ?? null), "baseurl", [], "any", false, false, false, 7), "html", null, true);
        echo "\">
          ";
        // line 8
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["site"] ?? null), "title", [], "any", false, false, false, 8), "html", null, true);
        echo "
        </a>
        <div>
          <p class=\"site-tagline uk-text-small uk-margin-remove\">";
        // line 11
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["site"] ?? null), "tagline", [], "any", false, false, false, 11), "html", null, true);
        echo "</p>
        </div>
      </div>
    </div>

    <div class=\"header-main-nav uk-navbar-center nav-overlay\">
      <ul class=\"uk-navbar-nav uk-visible@m\">
        <li class=\"menu-item\">
          <a href=\"";
        // line 19
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["site"] ?? null), "baseurl", [], "any", false, false, false, 19), "html", null, true);
        echo "\">Home</a>
        </li>
        <li class=\"menu-item\">
          <a href=\"";
        // line 22
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["site"] ?? null), "baseurl", [], "any", false, false, false, 22), "html", null, true);
        echo "/about\">About</a>
        </li>
        <li class=\"menu-item\">
          <a href=\"";
        // line 25
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["site"] ?? null), "baseurl", [], "any", false, false, false, 25), "html", null, true);
        echo "/policies\">Policies</a>
        </li>
        <li class=\"menu-item\">
          <a href=\"";
        // line 28
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["site"] ?? null), "baseurl", [], "any", false, false, false, 28), "html", null, true);
        echo "/contact/\">Contact</a>
        </li>
      </ul>
    </div>

    <div class=\"header-icons-nav uk-navbar-right\">

      <div class=\"search-toggle uk-visible@m\">
        <a class=\"uk-navbar-toggle\" href=\"#modal-navbar-search\" uk-toggle=\"\" aria-expanded=\"false\">
          <i class=\"fas fa-search\"></i>
        </a>
      </div>

      <ul class=\"mobile-menu-toggle uk-navbar-nav uk-hidden@m\">
        <li>
          <a class=\"uk-navbar-toggle\" href=\"#offcanvas-mobile-nav\" uk-toggle=\"\" aria-expanded=\"false\">
            <i class=\"fas fa-bars\"></i>
            <span class=\"uk-hidden\">Menu</span>
          </a>
        </li>
      </ul>

      <div class=\"dark-light-switcher pull-right-15\">
        <a id=\"DarkSwitch\" class=\"uk-navbar-toggle darklight-switch\"><i class=\"far fa-moon\"></i></a>
        <a id=\"LightSwitch\" class=\"uk-navbar-toggle darklight-switch\" hidden=\"\"><i class=\"fas fa-sun\"></i></a>
      </div>

    </div>

  </nav>

  <div id=\"modal-navbar-search\" class=\"uk-modal-full uk-modal\" uk-modal=\"\">
    <div class=\"uk-modal-dialog uk-flex uk-flex-center uk-flex-middle\" uk-height-viewport=\"\">
      <a class=\"uk-modal-close-full uk-link-toggle\">
        <i class=\"fas fa-times\"></i>
        <span class=\"uk-hidden\">Close</span>
      </a>
      <form class=\"uk-search uk-search-large\" role=\"search\" method=\"get\" action=\"https://rob.com\">
        <input id=\"header-search\" class=\"uk-search-input uk-text-center\" type=\"search\" placeholder=\"Start typing...\" name=\"s\" autofocus=\"\">
        <input type=\"hidden\" name=\"post_type\" value=\"post\">
      </form>
    </div>
  </div>
  <div id=\"offcanvas-mobile-nav\" uk-offcanvas=\"flip: true; overlay: true; mode: push\" class=\"uk-offcanvas\">
    <div class=\"uk-offcanvas-bar uk-offcanvas-bar-animation uk-offcanvas-slide\">

      <a class=\"uk-offcanvas-close\">
        <i class=\"fas fa-times\"></i>
        <span class=\"uk-hidden\">Close</span>
      </a>

      <ul class=\"uk-nav uk-nav-default\">
        <li class=\"uk-nav-header uk-text-bold uk-text-uppercase uk-margin-small-bottom\">Menu</li>
        <li>Add a menu to the Mobile Menu slot in Appearance &gt; Menus.</li>
        <li class=\"uk-nav-divider uk-margin-top\"></li>
        <div class=\"uk-margin-top uk-position-z-index uk-position-relative\">
          <form class=\"uk-search uk-search-default uk-width-1-1\" role=\"search\" method=\"get\" action=\"https://rob.com\">
            <span class=\"search-input-icon\"><i class=\"fas fa-search\"></i></span>
            <input class=\"uk-search-input\" type=\"search\" placeholder=\"Search...\" id=\"SearchMobile\" name=\"s\" autocomplete=\"off\">
          </form>
        </div>
      </ul>

    </div>
  </div>
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
        return array (  84 => 28,  78 => 25,  72 => 22,  66 => 19,  55 => 11,  49 => 8,  45 => 7,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<header id=\"SiteHeader\" class=\"site-header uk-background-default uk-padding-small uk-padding-remove-horizontal\">

  <nav class=\"uk-navbar-transparent uk-container uk-navbar\" uk-navbar=\"\">

    <div class=\"header-logo uk-navbar-left nav-logo-area\">
      <div class=\"\">
        <a class=\"site-logo uk-logo uk-padding-remove uk-text-bold uk-text-emphasis\" href=\"{{site.baseurl}}\">
          {{site.title}}
        </a>
        <div>
          <p class=\"site-tagline uk-text-small uk-margin-remove\">{{site.tagline}}</p>
        </div>
      </div>
    </div>

    <div class=\"header-main-nav uk-navbar-center nav-overlay\">
      <ul class=\"uk-navbar-nav uk-visible@m\">
        <li class=\"menu-item\">
          <a href=\"{{site.baseurl}}\">Home</a>
        </li>
        <li class=\"menu-item\">
          <a href=\"{{site.baseurl}}/about\">About</a>
        </li>
        <li class=\"menu-item\">
          <a href=\"{{site.baseurl}}/policies\">Policies</a>
        </li>
        <li class=\"menu-item\">
          <a href=\"{{site.baseurl}}/contact/\">Contact</a>
        </li>
      </ul>
    </div>

    <div class=\"header-icons-nav uk-navbar-right\">

      <div class=\"search-toggle uk-visible@m\">
        <a class=\"uk-navbar-toggle\" href=\"#modal-navbar-search\" uk-toggle=\"\" aria-expanded=\"false\">
          <i class=\"fas fa-search\"></i>
        </a>
      </div>

      <ul class=\"mobile-menu-toggle uk-navbar-nav uk-hidden@m\">
        <li>
          <a class=\"uk-navbar-toggle\" href=\"#offcanvas-mobile-nav\" uk-toggle=\"\" aria-expanded=\"false\">
            <i class=\"fas fa-bars\"></i>
            <span class=\"uk-hidden\">Menu</span>
          </a>
        </li>
      </ul>

      <div class=\"dark-light-switcher pull-right-15\">
        <a id=\"DarkSwitch\" class=\"uk-navbar-toggle darklight-switch\"><i class=\"far fa-moon\"></i></a>
        <a id=\"LightSwitch\" class=\"uk-navbar-toggle darklight-switch\" hidden=\"\"><i class=\"fas fa-sun\"></i></a>
      </div>

    </div>

  </nav>

  <div id=\"modal-navbar-search\" class=\"uk-modal-full uk-modal\" uk-modal=\"\">
    <div class=\"uk-modal-dialog uk-flex uk-flex-center uk-flex-middle\" uk-height-viewport=\"\">
      <a class=\"uk-modal-close-full uk-link-toggle\">
        <i class=\"fas fa-times\"></i>
        <span class=\"uk-hidden\">Close</span>
      </a>
      <form class=\"uk-search uk-search-large\" role=\"search\" method=\"get\" action=\"https://rob.com\">
        <input id=\"header-search\" class=\"uk-search-input uk-text-center\" type=\"search\" placeholder=\"Start typing...\" name=\"s\" autofocus=\"\">
        <input type=\"hidden\" name=\"post_type\" value=\"post\">
      </form>
    </div>
  </div>
  <div id=\"offcanvas-mobile-nav\" uk-offcanvas=\"flip: true; overlay: true; mode: push\" class=\"uk-offcanvas\">
    <div class=\"uk-offcanvas-bar uk-offcanvas-bar-animation uk-offcanvas-slide\">

      <a class=\"uk-offcanvas-close\">
        <i class=\"fas fa-times\"></i>
        <span class=\"uk-hidden\">Close</span>
      </a>

      <ul class=\"uk-nav uk-nav-default\">
        <li class=\"uk-nav-header uk-text-bold uk-text-uppercase uk-margin-small-bottom\">Menu</li>
        <li>Add a menu to the Mobile Menu slot in Appearance &gt; Menus.</li>
        <li class=\"uk-nav-divider uk-margin-top\"></li>
        <div class=\"uk-margin-top uk-position-z-index uk-position-relative\">
          <form class=\"uk-search uk-search-default uk-width-1-1\" role=\"search\" method=\"get\" action=\"https://rob.com\">
            <span class=\"search-input-icon\"><i class=\"fas fa-search\"></i></span>
            <input class=\"uk-search-input\" type=\"search\" placeholder=\"Search...\" id=\"SearchMobile\" name=\"s\" autocomplete=\"off\">
          </form>
        </div>
      </ul>

    </div>
  </div>
</header>", "header.twig", "C:\\xampp\\htdocs\\static.com\\app\\views\\parts\\header.twig");
    }
}
