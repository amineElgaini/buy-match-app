<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* login.twig */
class __TwigTemplate_c5958c03100e293c0a9f46c6d8c7c849 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield "<!DOCTYPE html>
<html lang=\"fr\">

<head>
    <meta charset=\"UTF-8\">
    <title>Connexion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background: #fff;
            padding: 30px;
            width: 360px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .2);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            background: #1e3c72;
            color: white;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #16315d;
        }

        .link {
            text-align: center;
            margin-top: 15px;
        }

        .link a {
            color: #1e3c72;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class=\"card\">
        <h2>";
        // line 68
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["title"] ?? null), "html", null, true);
        yield "</h2>

        <form method=\"POST\" action=\"/buy-match/login\">
            <input type=\"email\" name=\"email\" placeholder=\"Adresse email\" required>
            <input type=\"password\" name=\"password\" placeholder=\"Mot de passe\" required>
            <button type=\"submit\">Se connecter</button>
        </form>

        <div class=\"link\">
            <a href=\"/buy-match/register\">Créer un compte</a>
        </div>
    </div>

</body>

</html>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "login.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  111 => 68,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<!DOCTYPE html>
<html lang=\"fr\">

<head>
    <meta charset=\"UTF-8\">
    <title>Connexion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background: #fff;
            padding: 30px;
            width: 360px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .2);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            background: #1e3c72;
            color: white;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #16315d;
        }

        .link {
            text-align: center;
            margin-top: 15px;
        }

        .link a {
            color: #1e3c72;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class=\"card\">
        <h2>{{ title }}</h2>

        <form method=\"POST\" action=\"/buy-match/login\">
            <input type=\"email\" name=\"email\" placeholder=\"Adresse email\" required>
            <input type=\"password\" name=\"password\" placeholder=\"Mot de passe\" required>
            <button type=\"submit\">Se connecter</button>
        </form>

        <div class=\"link\">
            <a href=\"/buy-match/register\">Créer un compte</a>
        </div>
    </div>

</body>

</html>", "login.twig", "/var/www/html/buy-match/app/views/login.twig");
    }
}
