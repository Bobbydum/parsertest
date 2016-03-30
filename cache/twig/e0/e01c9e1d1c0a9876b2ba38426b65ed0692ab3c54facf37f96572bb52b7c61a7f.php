<?php

/* index.html.twig */
class __TwigTemplate_2b48f2cc6536c545ff9a472b29d66f751f69b040460e33e183f5292cdf0a88ea extends Twig_Template
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
        echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
        \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">
<head>
    <title>Upload file that you want to parse</title>
</head>
<body>
<b>";
        // line 8
        echo twig_escape_filter($this->env, (isset($context["user"]) ? $context["user"] : $this->getContext($context, "user")), "html", null, true);
        echo "</b>
<b>";
        // line 9
        echo twig_escape_filter($this->env, (isset($context["message"]) ? $context["message"] : $this->getContext($context, "message")), "html", null, true);
        echo "</b>
<form action=\"\" method=\"post\" ";
        // line 10
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'enctype');
        echo ">
    <fieldset>
        <legend>File Upload</legend>
        ";
        // line 13
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'widget');
        echo "
    </fieldset>
    <input type=\"submit\" name=\"Upload File\"/>
</form>
</body>
</html>";
    }

    public function getTemplateName()
    {
        return "index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  42 => 13,  36 => 10,  32 => 9,  28 => 8,  19 => 1,);
    }
}
/* <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"*/
/*         "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">*/
/* <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">*/
/* <head>*/
/*     <title>Upload file that you want to parse</title>*/
/* </head>*/
/* <body>*/
/* <b>{{ user }}</b>*/
/* <b>{{ message }}</b>*/
/* <form action="" method="post" {{ form_enctype(form) }}>*/
/*     <fieldset>*/
/*         <legend>File Upload</legend>*/
/*         {{ form_widget(form) }}*/
/*     </fieldset>*/
/*     <input type="submit" name="Upload File"/>*/
/* </form>*/
/* </body>*/
/* </html>*/
