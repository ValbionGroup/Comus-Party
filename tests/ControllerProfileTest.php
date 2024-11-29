<?php
/**
 * @file ControllerProfileTest.php
 * @brief Le fichier contient la déclaration et la définition de la classe controllerProfile
 * @author Conchez-Boueytou Robin
 * @date 14/11/2024
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
 * @brief Classe controllerProfileTest
 * @details La classe controllerProfileTest permet de tester les méthodes de la classe ControllerProfile
 */
class ControllerProfileTest extends TestCase
{

    private Controller $controller;

    protected function setUp(): void
    {
        $loader = new FilesystemLoader(__DIR__ . '/../src/templates');
        $twig = new Environment($loader);


        $this->controller = new ControllerProfile($loader, $twig);

    }

    /**
     * @brief Test de la méthode showByPlayer
     * @return void
     * @throws DateMalformedStringException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws NotFoundException
     */
    public function testShowByPlayerThrowsNotFoundExceptionOnNullUuid(): void
    {
        // S'assurer que le contrôleur n'est pas null
        $this->assertNotNull($this->controller, 'Controller is null.');

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Player not found');

        $this->controller->showByPlayer(null);
    }

}
