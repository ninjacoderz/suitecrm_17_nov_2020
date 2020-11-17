<?php

/* display/export/option_header.twig */
class __TwigTemplate_f449193e2a87d960ff6f64a34bf68c0cec90e9e271d592cbb367dffeab6625d7 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<div class=\"exportoptions\" id=\"header\">
    <h2>";
        // line 3
        echo PhpMyAdmin\Util::getImage("b_export", _gettext("Export"));
        // line 4
        if ((($context["export_type"] ?? null) == "server")) {
            // line 5
            echo _gettext("Exporting databases from the current server");
        } elseif ((        // line 6
($context["export_type"] ?? null) == "database")) {
            // line 7
            echo twig_escape_filter($this->env, sprintf(_gettext("Exporting tables from \"%s\" database"), ($context["db"] ?? null)), "html", null, true);
        } else {
            // line 9
            echo twig_escape_filter($this->env, sprintf(_gettext("Exporting rows from \"%s\" table"), ($context["table"] ?? null)), "html", null, true);
        }
        // line 11
        echo "    </h2>
</div>
";
    }

    public function getTemplateName()
    {
        return "display/export/option_header.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  36 => 11,  33 => 9,  30 => 7,  28 => 6,  26 => 5,  24 => 4,  22 => 3,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "display/export/option_header.twig", "/var/www/suitecrm/phpMyAdmin-4.8.0.1-all-languages/templates/display/export/option_header.twig");
    }
}
