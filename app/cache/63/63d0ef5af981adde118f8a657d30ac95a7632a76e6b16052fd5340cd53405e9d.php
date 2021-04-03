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

/* sidebar.twig */
class __TwigTemplate_d4ffc4254d7c62731f78c488beaa1da26ba889ea20324c0e6503febb588449f0 extends Template
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
        echo "<aside class=\"uk-width-1-3@m\">
  <button type=\"button\" data-link=\"https://rob.com/corporis-sint-deleniti-eos/\" hidden=\"\">Click me</button>
  <h3 class=\"uk-text-bold widget-title\">Sidebar</h3>
  <div class=\"uk-tile uk-tile-small uk-tile-muted\">
    Add some widgets to the Sidebar Widget Area in Appearance > Widgets.
  </div>
</aside>";
    }

    public function getTemplateName()
    {
        return "sidebar.twig";
    }

    public function getDebugInfo()
    {
        return array (  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<aside class=\"uk-width-1-3@m\">
  <button type=\"button\" data-link=\"https://rob.com/corporis-sint-deleniti-eos/\" hidden=\"\">Click me</button>
  <h3 class=\"uk-text-bold widget-title\">Sidebar</h3>
  <div class=\"uk-tile uk-tile-small uk-tile-muted\">
    Add some widgets to the Sidebar Widget Area in Appearance > Widgets.
  </div>
</aside>", "sidebar.twig", "C:\\xampp\\htdocs\\static.com\\app\\views\\parts\\sidebar.twig");
    }
}
