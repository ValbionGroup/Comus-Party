<?php
/**
 * @file    controllerPages.class.php
 * @brief   Ce fichier contient la déclaration & définition de la classe ControllerPages.
 * @author  Estéban DESESSARD & Lucas ESPIET
 * @date    14/12/2024
 * @version 1.0
 */

namespace ComusParty\Controllers;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

/**
 * @brief Classe ControllerPages
 * @details Contrôleur des différentes pages du site (Accueil, CGU, CGV, etc.)
 */
class ControllerPages extends Controller
{
    /**
     * @brief Constructeur de la classe ControllerPages
     * @param FilesystemLoader $loader Le loader de Twig
     * @param Environment $twig L'environnement de Twig
     */
    public function __construct(FilesystemLoader $loader, Environment $twig)
    {
        parent::__construct($loader, $twig);
    }

    public function showHomePage()
    {
        $template = $this->getTwig()->load('home.twig');
        echo $template->render();
    }

    /**
     * @brief Affiche la page des Conditions Générales d'Utilisation
     * @return void
     * @throws LoaderError Exception levée dans le cas d'une erreur de chargement
     * @throws RuntimeError Exception levée dans le cas d'une erreur d'exécution
     * @throws SyntaxError Exception levée dans le cas d'une erreur de syntaxe
     */
    public function showCgu()
    {
        $template = $this->getTwig()->load('cgu.twig');
        echo $template->render();
    }
}