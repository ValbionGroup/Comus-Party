<?php
/**
 * @file    controllerFactory.class.php
 * @author  Estéban DESESSARD
 * @brief   Le fichier contient la déclaration & définition de la classe ControllerFactory.
 * @date    12/11/2024
 * @version 0.1
 */

use models\ControllerNotFoundException;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * @brief   Classe ControllerFactory
 * @details La classe ControllerFactory est un patron de conception Factory
 * @remark  C'est une version simplifié du patron de conception Factory
 */
class ControllerFactory {
    /**
     * @brief La méthode getController permet de récupérer un contrôleur
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