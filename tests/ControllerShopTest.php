<?php
/**
 * @file ControllerShopTest.php
 * @brief Le fichier contient la déclaration et la définition de la classe ControllerShopTest
 * @author DESESSARD Estéban
 * @date 28/11/2024
 * @version 0.1
 */

use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * @brief Classe ControllerShopTest
 * @details La classe ControllerShopTest permet de tester les méthodes de la classe ControllerShop
 */
class ControllerShopTest extends TestCase
{
    /**
     * @brief Le contrôleur
     * @var Controller|ControllerProfile
     */
    private Controller $controller;

    /**
     * @brief Méthode setUp exécutée avant chaque test
     * @return void
     */
    protected function setUp(): void
    {
        $loader = new FilesystemLoader(__DIR__ . '/../src/templates');
        $twig = new Environment($loader);


        $this->controller = new ControllerProfile($loader, $twig);

    }
}
