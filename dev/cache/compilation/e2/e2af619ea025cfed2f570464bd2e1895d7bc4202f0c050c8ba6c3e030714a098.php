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

/* _about-content.twig */
class __TwigTemplate_9f92c6580cd8f3e1965a74d4b92f15b495f49a070d53246323a61d82729e5528 extends Template
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
        echo "  <p>This is the About page content</p>
  <p></p>";
    }

    public function getTemplateName()
    {
        return "_about-content.twig";
    }

    public function getDebugInfo()
    {
        return array (  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("  <p>This is the About page content</p>
  <p></p>", "_about-content.twig", "C:\\xampp\\htdocs\\static.com\\app\\views\\content\\_about-content.twig");
    }
}
