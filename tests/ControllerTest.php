<?php
/**
 * @file controllerTest.php
 * @brief Le fichier contient la déclaration et la définition de la classe GameDao
 * @author Conchez-Boueytou Robin
 * @date 14/11/2024
 * @version 0.1
 */
require_once  __DIR__ . '/../include.php';

use ComusParty\Controllers\Controller;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * @brief Classe ControllerTest
 * @details La classe ControllerTest permet de tester les méthodes de la classe Controller
 */
class ControllerTest extends TestCase
{
    /**
     * @brief Controller
     *
     * @var Controller
     */
    private Controller $controller;

    /**
     * @brief setUp
     * @return void
     */
    protected function setUp(): void
    {
        $this->controller = new Controller($loader = new FilesystemLoader(), $twig = new Environment($loader));
    }
    /**
     * @brief Test de la méthode getPdo
     * @return void
     */
    public function testGetPdo(): void
    {
        $this->assertInstanceOf(PDO::class, $this->controller->getPdo());
    }
    /**
     * @brief Test de la méthode setPdo
     * @return void
     */
    public function testSetPdo(): void
    {
        $this->controller->setPdo($pdo = new PDO('sqlite::memory:'));
        $this->assertEquals($pdo, $this->controller->getPdo());
    }

    /**
     * @brief Test de la méthode getLoader
     * @return void
     */
    public function testGetLoader(): void
    {
        $this->assertInstanceOf(FilesystemLoader::class, $this->controller->getLoader());
    }

    /**
     * @brief Test de la méthode setLoader
     * @return void
     */
    public function testSetLoader(): void
    {
        $this->controller->setLoader($loader = new FilesystemLoader(__DIR__.'/../src/templates'));
        $this->assertEquals($loader, $this->controller->getLoader());
    }

    /**
     * @brief Test de la méthode getTwig
     * @return void
     */
    public function testGetTwig(): void
    {
        $this->assertInstanceOf(Environment::class, $this->controller->getTwig());
    }

    /**
     * @brief Test de la méthode setTwig
     * @return void
     */
    public function testSetTwig(): void
    {
        $this->controller->setTwig($twig = new Environment($loader = new FilesystemLoader()));
        $this->assertEquals($twig, $this->controller->getTwig());
    }

    /**
     * @brief Test de la méthode getGet
     * @return void
     */
    public function testGetGet(): void
    {
        $this->assertEquals(null, $this->controller->getGet());
    }

    /**
     * @brief Test de la méthode setGet
     * @return void
     */
    public function testSetGet(): void
    {
        $this->controller->setGet(["email" => $_GET['email'],"password" => $_GET['password']]);
        $this->assertEquals(["email" => $_GET['email'],"password" => $_GET['password']], $this->controller->getGet());
    }

    /**
     * @brief Test de la méthode getPost
     * @return void
     */
    public function testGetPost(): void
    {
        $this->assertEquals(null, $this->controller->getPost());
    }

    /**
     * @brief Test de la méthode setPost
     * @return void
     */
    public function testSetPost(): void
    {
        $this->controller->setPost(["email" => $_POST['email'],"password" => $_POST['password']]);
        $this->assertEquals(["email" => $_POST['email'],"password" => $_POST['password']], $this->controller->getPost());
    }
    /**
     * @brief Test de la méthode call
     * @return void
     * @throws \models\MethodNotFoundException
     */
    public function testCall():void{
        $this->assertEquals(null, $this->controller->call('getGet'));
    }

}
