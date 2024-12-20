<?php

require_once __DIR__ . '/../include.php';

use ComusParty\Controllers\Controller;
use ComusParty\Controllers\ControllerSuggestion;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ControllerSuggestionTest extends TestCase
{
    private Controller $controller;

    public function testSendSuggestionWithValidDatas(): void
    {
        // Démarrer la session si ce n'est pas déjà fait
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Simuler les données de session nécessaires
        $_SESSION['uuid'] = 'uuid1';
        $_SESSION['username'] = 'JohnDoe';

        $this->assertNotNull($this->controller, 'Controller is null.');
        $this->controller->sendSuggestion('game', 'test de suggestion');

        // Optionnel : nettoyer la session après le test
        session_destroy();
    }

    public function testSendSuggestionWithInvalidObjectThrowUnhandledMatchError(): void
    {
        // Démarrer la session si ce n'est pas déjà fait
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Simuler les données de session nécessaires
        $_SESSION['uuid'] = 'uuid1';
        $_SESSION['username'] = 'JohnDoe';

        $this->expectException(UnhandledMatchError::class);
        $this->controller->sendSuggestion('object-inconnu', 'test de suggestion');

        // Optionnel : nettoyer la session après le test
        session_destroy();
    }

    public function testSendSuggestionWithUnexistantUuidThrowPDOException(): void
    {
        // Démarrer la session si ce n'est pas déjà fait
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Simuler les données de session nécessaires
        $_SESSION['uuid'] = 'uuid-inexistant';
        $_SESSION['username'] = 'JohnDoe';

        $this->expectException(PDOException::class);
        $this->controller->sendSuggestion('game', 'test de suggestion');

        // Optionnel : nettoyer la session après le test
        session_destroy();
    }

    public function testSendSuggestionWithUnexistantUsernameDontThrowPDOException(): void
    {
        // Démarrer la session si ce n'est pas déjà fait
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Simuler les données de session nécessaires
        $_SESSION['uuid'] = 'uuid1';
        $_SESSION['username'] = 'username-inexistant';

        $this->assertNotNull($this->controller, 'Controller is null.');
        $this->controller->sendSuggestion('game', 'test de suggestion');

        // Optionnel : nettoyer la session après le test
        session_destroy();
    }

    public function testSendSuggestionWithNullUuidThrowPDOException(): void
    {
        // Démarrer la session si ce n'est pas déjà fait
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Simuler les données de session nécessaires
        $_SESSION['uuid'] = null;
        $_SESSION['username'] = 'JohnDoe';

        $this->expectException(PDOException::class);
        $this->controller->sendSuggestion('game', 'test de suggestion');

        // Optionnel : nettoyer la session après le test
        session_destroy();
    }

    protected function setUp(): void
    {
        $loader = new FilesystemLoader(__DIR__ . '/../src/templates');
        $twig = new Environment($loader);


        $this->controller = new ControllerSuggestion($loader, $twig);

    }
}
