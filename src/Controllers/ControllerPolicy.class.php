<?php
/**
 * @file    ControllerPolicy.class.php
 * @brief   Fichier de déclaration et définition de la classe ControllerPolicy
 * @author  Estéban DESESSARD
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
 * @brief Classe ControllerPolicy
 * @details Contrôleur des différentes pages de politiques du site (CGU, CGV, etc.)
 */
class ControllerPolicy extends Controller
{
    /**
     * @brief Constructeur de la classe ControllerPolicy
     * @param FilesystemLoader $loader Le loader de Twig
     * @param Environment $twig L'environnement de Twig
     */
    public function __construct(FilesystemLoader $loader, Environment $twig)
    {
        parent::__construct($loader, $twig);
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