<?php

use models\ControllerNotFoundException;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ControllerFactory {
    /**
     * @param $controller
     * @param FilesystemLoader $loader
     * @param Environment $twig
     * @return mixed
     * @throws ControllerNotFoundException
     */
    public static function getController($controller, FilesystemLoader $loader, Environment $twig): mixed
    {
        $controllerName = "Controller" . ucfirst($controller);

        if (!class_exists($controllerName)) {
            throw new ControllerNotFoundException('Controller ' . $controllerName . ' not found');
        }
        return new $controllerName($loader, $twig);
    }
}