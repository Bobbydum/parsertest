<?php

/* index.html.twig */

class __TwigTemplate_9c95705d9d11b81968171965e2042f60076057dccf852657c4c53f133a27fd41 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array();
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
        return array(37 => 11, 31 => 8, 27 => 7, 19 => 1,);
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">
<head>
    <title>Silex File Upload Example</title>
</head>
<body>
<b>";
        // line 7
        echo twig_escape_filter($this->env,
            (isset($context["message"]) ? $context["message"] : $this->getContext($context, "message")), "html", null,
            true);
        echo "</b>
<form action=\"\" method=\"post\" ";
        // line 8
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context,
            "form")), 'enctype');
        echo ">
    <fieldset>
        <legend>File Upload</legend>
        ";
        // line 11
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context,
            "form")), 'widget');
        echo "
    </fieldset>
    <input type=\"submit\" name=\"Upload File\" />
</form>
</body>
</html>";
    }
}
/* <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">*/
/* <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">*/
/* <head>*/
/*     <title>Silex File Upload Example</title>*/
/* </head>*/
/* <body>*/
/* <b>{{message}}</b>*/
/* <form action="" method="post" {{ form_enctype(form) }}>*/
/*     <fieldset>*/
/*         <legend>File Upload</legend>*/
/*         {{ form_widget(form) }}*/
/*     </fieldset>*/
/*     <input type="submit" name="Upload File" />*/
/* </form>*/
/* </body>*/
/* </html>*/
