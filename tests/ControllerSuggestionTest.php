<?php
/**
 * @file ControllerSuggestion.php
 * @brief Le fichier contient la déclaration et la définition de la classe Suggestion
 * @author Estéban DESESSARD
 * @date 20/12/2024
 * @version 0.1
 */

require_once __DIR__ . '/../include.php';

use ComusParty\Controllers\Controller;
use ComusParty\Controllers\ControllerSuggestion;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * @brief Classe ControllerSuggestionTest
 * @details La classe ControllerSuggestionTest permet de tester les méthodes de la classe ControllerSuggestion
 */
class ControllerSuggestionTest extends TestCase
{
    /**7
     * @brief Controller
     * @var Controller|ControllerSuggestion
     */
    private Controller $controller;

    /**
     * @brief Test de la méthode sendSuggestion avec des données valides
     * @return void
     */
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

    /**
     * @brief Test de la méthode sendSuggestion avec un objet invalide
     * @return void
     */
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

    /**
     * @brief Test de la méthode sendSuggestion avec un UUID inexistant en base de données
     * @return void
     */
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

    /**
     * @brief Test de la méthode sendSuggestion avec un username inexistant en base de données
     * @details Le nom d'utilisateur ne pose aucun problème car il ne constitue aucune clé étrangère en base de données qui pourrait empêcher l'insertion de la suggestion
     * @return void
     */
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

    /**
     * @brief Test de la méthode sendSuggestion avec un UUID null
     * @return void
     */
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
