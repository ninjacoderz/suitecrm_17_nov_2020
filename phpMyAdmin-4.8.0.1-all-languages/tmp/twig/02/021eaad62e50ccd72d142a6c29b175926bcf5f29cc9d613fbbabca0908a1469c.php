<?php

/* display/results/table_navigation_button.twig */
class __TwigTemplate_654d0b5b8251eafc5a690b163c7b406b881aee362b78bd6e6377e4e743cac778 extends Twig_Template
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
        echo "<td>
    <form action=\"sql.php\" method=\"post\"";
        // line 2
        echo ($context["onsubmit"] ?? null);
        echo ">";
        // line 3
        echo PhpMyAdmin\Url::getHiddenInputs(($context["db"] ?? null), ($context["table"] ?? null));
        echo "
        <input type=\"hidden\" name=\"sql_query\" value=\"";
        // line 4
        echo ($context["sql_query"] ?? null);
        echo "\" />
        <input type=\"hidden\" name=\"pos\" value=\"";
        // line 5
        echo twig_escape_filter($this->env, ($context["pos"] ?? null), "html", null, true);
        echo "\" />
        <input type=\"hidden\" name=\"is_browse_distinct\" value=\"";
        // line 6
        echo twig_escape_filter($this->env, ($context["is_browse_distinct"] ?? null), "html", null, true);
        echo "\" />
        <input type=\"hidden\" name=\"goto\" value=\"";
        // line 7
        echo twig_escape_filter($this->env, ($context["goto"] ?? null), "html", null, true);
        echo "\" />";
        // line 8
        echo ($context["input_for_real_end"] ?? null);
        echo "
        <input type=\"submit\" name=\"navig\" class=\"ajax\" value=\"";
        // line 9
        echo ($context["caption_output"] ?? null);
        echo "\" title=\"";
        echo twig_escape_filter($this->env, ($context["title"] ?? null), "html", null, true);
        echo "\"";
        // line 10
        echo ($context["onclick"] ?? null);
        echo " />
    </form>
</td>
";
    }

    public function getTemplateName()
    {
        return "display/results/table_navigation_button.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  53 => 10,  48 => 9,  44 => 8,  41 => 7,  37 => 6,  33 => 5,  29 => 4,  25 => 3,  22 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "display/results/table_navigation_button.twig", "/var/www/suitecrm/phpMyAdmin-4.8.0.1-all-languages/templates/display/results/table_navigation_button.twig");
    }
}
