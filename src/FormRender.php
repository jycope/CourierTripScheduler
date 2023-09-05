<?php

namespace App;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class FormRender {
    public static function render(string $pathToFile, array $data = [])
    {
        $loader = new FilesystemLoader('./views');
        $twig = new Environment($loader);
        $template = $twig->load($pathToFile);

        echo $template->render($data);
    }
}