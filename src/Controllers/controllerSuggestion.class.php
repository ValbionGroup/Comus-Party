<?php
/**
 * @file    controllerSuggestion.class.php
 * @author  Estéban DESESSARD
 * @brief   Fichier de déclaration et définition de la classe ControllerSuggestion
 * @date    12/11/2024
 * @version 0.1
 */

namespace ComusParty\Controllers;

use ComusParty\App\MessageHandler;
use ComusParty\Models\Suggestion;
use ComusParty\Models\SuggestionDAO;
use ComusParty\Models\SuggestObject;
use Exception;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * @brief   Classe ControllerSuggestion
 * @details La classe ControllerSuggestion permet de gérer actions relatives aux suggestions effectuées par les utilisateurs
 */
class ControllerSuggestion extends Controller
{
    /**
     * @brief Constructeur de la classe ControllerShop
     * @param FilesystemLoader $loader Le loader de Twig
     * @param Environment $twig L'environnement de Twig
     */
    public function __construct(FilesystemLoader $loader, Environment $twig)
    {
        parent::__construct($loader, $twig);
    }

    /**
     * @brief Permet d'envoyer une suggestion et de l'insérer en base de données
     * @param string|null $object L'objet de la suggestion
     * @param string $content Le contenu de la suggestion rédigée
     * @return void
     */
    public function sendSuggestion(?string $object, string $content): void
    {
        $objectTyped = match ($object) {
            'bug' => SuggestObject::BUG,
            'game' => SuggestObject::GAME,
            'ui' => SuggestObject::UI,
            'other' => SuggestObject::OTHER,
            default => null
        };
        $suggestionObject = new Suggestion(null, $objectTyped, $content, $_SESSION['uuid'], $_SESSION['username']);
        $managerSuggestion = new SuggestionDAO($this->getPdo());
        if ($managerSuggestion->create($suggestionObject)) {
            MessageHandler::addMessageParametersToSession('Suggestion envoyée avec succès');
        } else {
            MessageHandler::addExceptionParametersToSession(new Exception('Erreur lors de l\'envoi de la suggestion'));
        }
        header('Location: /');
    }
}