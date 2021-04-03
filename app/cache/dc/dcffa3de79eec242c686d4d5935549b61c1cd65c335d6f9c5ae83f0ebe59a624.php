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
        echo "<footer id=\"SiteFooter\" class=\"site-footer uk-section uk-section-small uk-section-muted\">
  <div class=\"uk-container\">
\t   <p class=\"uk-text-small uk-text-center\">Add some widgets to the Footer Widget Area in Appearance > Widgets.</p>
  </div>
</footer>";
    }

    public function getTemplateName()
    {
        return "footer.twig";
    }

    public function getDebugInfo()
    {
        return array (  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<footer id=\"SiteFooter\" class=\"site-footer uk-section uk-section-small uk-section-muted\">
  <div class=\"uk-container\">
\t   <p class=\"uk-text-small uk-text-center\">Add some widgets to the Footer Widget Area in Appearance > Widgets.</p>
  </div>
</footer>", "footer.twig", "C:\\xampp\\htdocs\\static.com\\app\\views\\parts\\footer.twig");
    }
}
