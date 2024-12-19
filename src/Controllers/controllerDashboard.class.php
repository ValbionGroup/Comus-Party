<?php
/**
 * @file controllerDashboard.class.php
 * @brief Le fichier contient la déclaration et la définition de la classe ControllerDashboard
 * @author Estéban DESESSARD
 * @date 18/12/2024
 * @version 1.0
 */

namespace ComusParty\Controllers;

use ComusParty\Models\SuggestionDAO;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

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
     */
    public function showDashboard()
    {
        $suggestsManager = new SuggestionDAO($this->getPdo());
        $suggestions = $suggestsManager->findAllWaiting();
        $template = $this->getTwig()->load('moderator/dashboard.twig');
        echo $template->render(array(
            "suggestions" => $suggestions
        ));
    }

    public function denySuggestion($id)
    {
        $suggestsManager = new SuggestionDAO($this->getPdo());
        if ($suggestsManager->deny($id)) {
            echo json_encode([
                'success' => true,
                'message' => "Suggestion rejetée avec succès !",
            ]);
            exit;
        } else {
            echo json_encode([
                'success' => false,
                'message' => "Erreur lors du rejet de la suggestion",
            ]);
            exit;
        }
    }
}