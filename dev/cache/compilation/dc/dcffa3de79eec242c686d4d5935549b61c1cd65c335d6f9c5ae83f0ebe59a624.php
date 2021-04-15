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

/* footer.twig */
class __TwigTemplate_b0e62c497bc8c23f77e9f24dd45646b94545988a90a53f2f4f136607f0937872 extends Template
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
  /* footer */
  .site-footer .uk-subnav>*>:first-child {
    font-size: 1rem;
    text-transform: capitalize;
  }
  .site-footer .uk-subnav>* {
    padding-left: 30px;
  }
</style>
<footer id=\"SiteFooter\" class=\"site-footer uk-section uk-section-small uk-section-muted theme-border-top\">
  <div class=\"uk-container uk-text-center\">
    <p>© ";
        // line 13
        echo twig_escape_filter($this->env, twig_date_format_filter($this->env, "now", "Y"), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["configs"] ?? null), "site_title", [], "any", false, false, false, 13), "html", null, true);
        echo ". All Rights Reserved.</p>
  </div>
</footer>";
    }

    public function getTemplateName()
    {
        return "footer.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  51 => 13,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<style>
  /* footer */
  .site-footer .uk-subnav>*>:first-child {
    font-size: 1rem;
    text-transform: capitalize;
  }
  .site-footer .uk-subnav>* {
    padding-left: 30px;
  }
</style>
<footer id=\"SiteFooter\" class=\"site-footer uk-section uk-section-small uk-section-muted theme-border-top\">
  <div class=\"uk-container uk-text-center\">
    <p>© {{ 'now' | date('Y') }} {{configs.site_title}}. All Rights Reserved.</p>
  </div>
</footer>", "footer.twig", "C:\\xampp\\htdocs\\static.com\\app\\views\\parts\\footer.twig");
    }
}
