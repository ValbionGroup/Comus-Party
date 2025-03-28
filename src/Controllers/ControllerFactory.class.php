<?php
/**
 * @file    ControllerFactory.class.php
 * @author  Estéban DESESSARD
 * @brief   Fichier de déclaration et définition de la classe ControllerFactory
 * @date    12/11/2024
 * @version 0.1
 */

namespace ComusParty\Controllers;

use ComusParty\App\Exceptions\ControllerNotFoundException;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * @brief   Classe ControllerFactory
 * @details La classe ControllerFactory est un patron de conception Factory
 * @remark  C'est une version simplifiée du patron de conception Factory
 */
class ControllerFactory
{
    /**
     * @brief La méthode getController permet de récupérer un contrôleur
     * @param string $controller Le nom du contrôleur
     * @param FilesystemLoader $loader Le loader de Twig
     * @param Environment $twig L'environnement de Twig
     * @return Controller Objet retourné par la méthode, ici un contrôleur général
     * @throws ControllerNotFoundException Exception levée dans le cas où le contrôleur n'est pas trouvé
     */
    public static function getController(string $controller, FilesystemLoader $loader, Environment $twig): Controller
    {
        $controllerName = 'ComusParty\Controllers\Controller' . ucfirst($controller);

        if (!class_exists($controllerName)) {
            throw new ControllerNotFoundException('Controller ' . $controllerName . ' not found');
        }
        return new $controllerName($loader, $twig);
    }
}