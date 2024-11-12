<?php
/**
 * @file    controllerFactory.class.php
 * @author  Estéban DESESSARD
 * @brief   Le fichier contient la déclaration & définition de la classe ControllerFactory.
 * @date    12/11/2024
 * @version 0.1
 */

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * @brief   La classe ControllerFactory est un patron de conception Factory
 * @remark  C'est une version simplifié du patron de conception Factory
 */
class ControllerFactory {
    /**
     * @brief                           La méthode getController permet de récupérer un contrôleur
     * @param string $controller Le nom du contrôleur
     * @param FilesystemLoader $loader Le loader de Twig
     * @param Environment $twig L'environnement de Twig
     * @return mixed                    Objet retourné par la méthode, ici un contrôleur
     * @throws Exception                Exception levée dans le cas de l'inexistance du contrôleur fourni
     */
    public static function getController(string $controller, FilesystemLoader $loader, Environment $twig)
    {
        $controllerName = "Controller" . ucfirst($controller);
        if (!class_exists($controllerName)) {
            throw new Exception('Controller ' . $controllerName . ' not found');
        }
        return new $controllerName($loader, $twig);
    }
}