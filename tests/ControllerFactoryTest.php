<?php
/**
 * @file ControllerFactoryTest.php
 * @brief Le fichier contient la déclaration et la définition de la classe GameDao
 * @author Conchez-Boueytou Robin
 * @date 14/11/2024
 * @version 0.1
 */


require_once  __DIR__ . '/../include.php';

use PHPUnit\Framework\TestCase;
/**
 * @brief Classe ControllerFactoryTest
 * @details La classe ControllerFactoryTest permet de tester les méthodes de la classe ControllerFactory
 */
class ControllerFactoryTest extends TestCase
{
    /**
     * @brief Test de la méthode getController
     */
    public function testGetController()
    {
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../src/templates');
        $twig = new \Twig\Environment($loader);

        $controller = \ControllerFactory::getController('game', $loader, $twig);
        $this->assertInstanceOf(\ControllerGame::class, $controller);
    }

}