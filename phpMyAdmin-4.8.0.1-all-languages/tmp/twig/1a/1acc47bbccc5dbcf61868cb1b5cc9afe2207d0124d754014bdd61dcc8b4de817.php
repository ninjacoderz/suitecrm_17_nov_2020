<?php

/* console/query_action.twig */
class __TwigTemplate_7b7547ef3432e47428f7971e6ff76f1dfc8b6def1694da3f6605566854bcc608 extends Twig_Template
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
        echo "<div class=\"";
        echo twig_escape_filter($this->env, ($context["parent_div_classes"] ?? null), "html", null, true);
        echo "\">";
        // line 2
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["content_array"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["content"]) {
            // line 3
            if (array_key_exists("content", $context)) {
                // line 4
                echo "        <span class=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($context["content"], 0, array(), "array"), "html", null, true);
                echo "\">";
                // line 5
                echo twig_escape_filter($this->env, $this->getAttribute($context["content"], 1, array(), "array"), "html", null, true);
                // line 6
                if ($this->getAttribute($context["content"], "extraSpan", array(), "array", true, true)) {
                    // line 7
                    echo "                : <span>";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["content"], "extraSpan", array(), "array"), "html", null, true);
                    echo "</span>";
                }
                // line 9
                echo "        </span>";
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['content'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 12
        echo "</div>
";
    }

    public function getTemplateName()
    {
        return "console/query_action.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  49 => 12,  42 => 9,  37 => 7,  35 => 6,  33 => 5,  29 => 4,  27 => 3,  23 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "console/query_action.twig", "/var/www/suitecrm/phpMyAdmin-4.8.0.1-all-languages/templates/console/query_action.twig");
    }
}
