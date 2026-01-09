<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View
{
    private static Environment $twig;

    public static function init(): void
    {
        if (!isset(self::$twig)) {
            $loader = new FilesystemLoader(__DIR__ . '/../views');

            self::$twig = new Environment($loader, [
                'cache' => __DIR__ . '/../../cache',
                'auto_reload' => true,
                'debug' => true,
            ]);
        }
    }

    public static function render(string $view, array $data = []): void
    {
        self::init();
        echo self::$twig->render($view . '.twig', $data);
    }
}
