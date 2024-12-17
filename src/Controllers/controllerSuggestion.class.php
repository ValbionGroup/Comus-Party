<?php
/**
 * @file    controllerSuggestion.class.php
 * @author  Estéban DESESSARD
 * @brief   Le fichier contient la déclaration & définition de la classe ControllerSuggestion.
 * @date    12/11/2024
 * @version 0.1
 */

namespace ComusParty\Controllers;

use ComusParty\Models\Suggestion;
use ComusParty\Models\SuggestionDAO;
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

    public function sendSuggestion($suggestion)
    {
        $suggestion = new Suggestion($suggestion, $_SESSION['uuid']);
        $managerSuggestion = new SuggestionDAO($this->getPdo());
        $managerSuggestion->create($suggestion);
    }
}