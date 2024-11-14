<?php

require_once  __DIR__ . '/../include.php';

use models\NotFoundException;
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
