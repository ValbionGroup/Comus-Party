<?php
/**
 * @file controllerDashboard.class.php
 * @brief Le fichier contient la déclaration et la définition de la classe ControllerDashboard
 * @author Estéban DESESSARD
 * @date 18/12/2024
 * @version 1.0
 */

namespace ComusParty\Controllers;

use ComusParty\App\MessageHandler;
use ComusParty\Models\ReportDAO;
use ComusParty\Models\SuggestionDAO;
use DateMalformedStringException;
use Exception;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

/**
 * @biref Classe ControllerDashboard
 * @details La classe gère les actions relatives au dashboard de modération
 */
class ControllerDashboard extends Controller
{
    /**
     * @brief Constructeur de la classe ControllerDashboard
     * @param FilesystemLoader $loader
     * @param Environment $twig
     */
    public function __construct(FilesystemLoader $loader, Environment $twig)
    {
        parent::__construct($loader, $twig);
    }

    /**
     * @brief Affiche la page d'accueil du dashboard
     * @return void
     * @throws LoaderError Exception levée dans le cas d'une erreur de chargement du template
     * @throws RuntimeError Exception levée dans le cas d'une erreur d'exécution
     * @throws SyntaxError Exception levée dans le cas d'une erreur de syntaxe
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     */
    public function showDashboard()
    {
        $suggestsManager = new SuggestionDAO($this->getPdo());
        $suggestions = $suggestsManager->findAllWaiting();

        $reportsManager = new ReportDAO($this->getPdo());
        $reports = $reportsManager->findAllWaiting();

        $template = $this->getTwig()->load('moderator/dashboard.twig');
        echo $template->render(array(
            "suggestions" => $suggestions,
            "reports" => $reports
        ));
    }

    /**
     * @brief Refuse une suggestion et affiche le résultat de l'exécution de la requête en base de données
     * @param int $id L'identifiant de la suggestion à refuser
     * @return void
     */
    public function denySuggestion(int $id)
    {
        $suggestsManager = new SuggestionDAO($this->getPdo());
        echo json_encode(['success' => $suggestsManager->deny($id)]);
    }

    /**
     * @brief Accepte une suggestion et affiche le résultat de l'exécution de la requête en base de données
     * @param int $id L'identifiant de la suggestion à refuser
     * @return void
     */
    public function acceptSuggestion(int $id)
    {
        $suggestsManager = new SuggestionDAO($this->getPdo());
        echo json_encode(['success' => $suggestsManager->accept($id)]);
        exit;
    }

    /**
     * @brief Récupère les informations à propos d'une sugestion et les renvoi sous format JSON
     * @param int|null $id L'identifiant de la suggestion à récupérer
     * @return void
     * @throws DateMalformedStringException
     */
    public function getSuggestionInfo(?int $id)
    {
        $suggestsManager = new SuggestionDAO($this->getPdo());
        $suggestion = $suggestsManager->findById($id);
        echo json_encode([
            "success" => true,
            "suggestion" => [
                "id" => $suggestion->getId(),
                "object" => $suggestion->getObject()->name,
                "content" => $suggestion->getContent(),
                "author_username" => $suggestion->getAuthorUsername(),
            ],
        ]);
        exit;
    }

    /**
     * @brief Récupère toutes les suggestions en attente et les renvoi sous format JSON
     * @return void
     */
    public function getAllSuggestionsWaiting(): void {
        $suggestsManager = new SuggestionDAO($this->getPdo());
        $suggestions = $suggestsManager->findAllWaiting();
        if (empty($suggestions)) {
            echo json_encode([
                "success" => true,
                "suggestions" => null,
            ]);
            exit;
        }
        echo json_encode([
            "success" => true,
            "suggestions" => array_map(fn($suggestion) => [
                "id" => $suggestion->getId(),
                "object" => $suggestion->getObject()->name,
                "content" => $suggestion->getContent(),
                "created_at" => $suggestion->getCreatedAt()->getTimestamp() * 1000,
            ], $suggestions),
        ]);
        exit;
    }

    /**
     * @brief Récupère tous les signalements en attente et les renvoi sous format JSON
     * @return void
     * @throws DateMalformedStringException
     */
    public function getAllReports(): void {
        $reportsManager = new ReportDAO($this->getPdo());
        $reports = $reportsManager->findAllWaiting();
        if (empty($reports)) {
            echo json_encode([
                "success" => true,
                "reports" => null,
            ]);
            exit;
        }
        echo json_encode([
            "success" => true,
            "reports" => array_map(fn($report) => [
                "id" => $report->getId(),
                "object" => $report->getObject()->name,
                "description" => $report->getDescription(),
                "created_at" => $report->getCreatedAt()->getTimestamp() * 1000,
            ], $reports),
        ]);
        exit;
    }
}