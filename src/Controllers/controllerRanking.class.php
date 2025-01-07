<?php

namespace ComusParty\Controllers;

use Twig\Environment;
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
     */
    public function showRanking() {
        $template = $this->getTwig()->load('ranking.twig');
        echo $template->render();
    }
}