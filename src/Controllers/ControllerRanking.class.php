<?php
/**
 * @file    ControllerRanking.class.php
 * @brief   Fichier de déclaration et définition de la classe ControllerRanking
 * @author  Estéban DESESSARD
 * @date    08/01/2025
 * @version 0.1
 */

namespace ComusParty\Controllers;

use ComusParty\Models\PlayerDAO;
use DateMalformedStringException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

/**
 * @brief Classe ControllerRanking
 * @details La classe ControllerRanking permet de gérer les actions liées au classement
 */
class ControllerRanking extends Controller
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
     * @brief Permet d'afficher le classement
     * @return void
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     * @throws LoaderError Exception levée dans le cas d'une erreur de chargement du loader
     * @throws RuntimeError Exception levée dans le cas d'une erreur d'exécution
     * @throws SyntaxError Exception levée dans le cas d'une erreur de syntaxe
     */
    public function showRanking()
    {
        $playerManager = new PlayerDAO($this->getPdo());
        $players = $playerManager->findInRangeOrderByEloDescWithDetails(1, 100);
        $template = $this->getTwig()->load('ranking.twig');
        echo $template->render(array(
            'players' => $players
        ));
    }
}