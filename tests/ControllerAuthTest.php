<?php
/**
 * @file ControllerAuth.php
 * @brief Le fichier contient la déclaration et la définition de la classe GameDao
 * @author DESESSARD Estéban
 * @date 15/11/2024
 * @version 0.1
 */

require_once __DIR__ . '/../include.php';

use ComusParty\Controllers\Controller;
use ComusParty\Controllers\ControllerAuth;
use ComusParty\Models\Exception\AuthentificationException;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * @brief Classe ControllerAuthTest
 * @details La classe ControllerAuthTest permet de tester les méthodes de la classe ControllerAuth
 */
class ControllerAuthTest extends TestCase
{
    /**
     * @brief Le contrôleur
     * @var Controller
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
        $this->controller = new ControllerAuth($loader, $twig);

    }

    /**
     * @brief Test de la méthode authenticate() avec un e-mail null
     * @return void
     * @throws DateMalformedStringException Exception levée dans le cas d'une erreur de format de date
     */
    public function testAuthenticateOnNullEmail(): void
    {
        $this->assertNotNull($this->controller, 'Controller is null.');
        $this->expectException(AuthentificationException::class);
        $this->controller->authenticate(null, 'hashed_password1');
    }

    /**
     * @brief Test de la méthode authenticate() avec un mot de passe null
     * @return void
     * @throws DateMalformedStringException Exception levée dans le cas d'une erreur de format de date
     */
    public function testAuthenticateOnNullPassword(): void
    {
        $this->assertNotNull($this->controller, 'Controller is null.');
        $this->expectException(AuthentificationException::class);
        $this->controller->authenticate('john.doe@example.com', null);
    }

    /**
     * @brief Test de la méthode authenticate() avec un e-mail et un mot de passe valide
     * @return void
     */
    public function testAuthenticateOnValidEmailAndPassword(): void
    {
        $this->assertNotNull($this->controller, 'Controller is null.');
        $this->controller->authenticate('john.doe@example.com', 'hashed_password1');
    }

    /**
     * @brief Test de la méthode authenticate() avec un e-mail invalide et un mot de passe valide
     * @return void
     */
    public function testAuthenticateOnInvalidEmailAndValidPassword(): void
    {
        $this->assertNotNull($this->controller, 'Controller is null.');
        $this->expectException(AuthentificationException::class);
        $this->controller->authenticate('john.doeexample.com', 'hashed_password1');
    }

    /**
     * @brief Test de la méthode authenticate() avec un e-mail inexistant en base de données et un mot de passe valide
     * @return void
     */
    public function testAuthenticateOnInexistantEmailAndValidPassword(): void
    {
        $this->assertNotNull($this->controller, 'Controller is null.');
        $this->expectException(AuthentificationException::class);
        $this->controller->authenticate('john.danny@example.com', 'hashed_password1');
    }
}
