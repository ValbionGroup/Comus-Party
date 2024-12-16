<?php
/**
 * @file ControllerShopTest.php
 * @brief Le fichier contient la déclaration et la définition de la classe controllerShop
 * @author Rivrais--Nowakowski Mathis
 * @date 11/12/2024
 * @version 0.1
 */


require_once  __DIR__ . '/../include.php';

use ComusParty\Controllers\Controller;
use ComusParty\Controllers\ControllerProfile;
use ComusParty\Models\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

/**
 * @brief Classe controllerShopTest
 * @details La classe controllerShopTest permet de tester les méthodes de la classe ControllerShop
 */
class ControllerShopTest extends TestCase
{

    private Controller $controller;

    protected function setUp(): void
    {
        $loader = new FilesystemLoader(__DIR__ . '/../src/templates');
        $twig = new Environment($loader);


        $this->controller = new ControllerShop($loader, $twig);

    }




}

