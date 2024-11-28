<?php

/**
 * @file    controllerShop.class.php
 * @author  Mathis RIVRAIS--NOWAKOWSKI
 * @brief   Le fichier contient la déclaration & définition de la classe ControllerShop.
 * @date    13/11/2024
 * @version 0.2
 */

namespace ComusParty\Controllers;

use ComusParty\Models\ArticleDAO;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

/**
 * @brief Classe ControllerShop
 * @details La classe ControllerShop permet de gérer les actions liées à un article
 */
class ControllerShop extends Controller {
    /**
     * @brief Constructeur de la classe ControllerShop
     * @param FilesystemLoader $loader Le loader de Twig
     * @param Environment $twig L'environnement de Twig
     */
    public function __construct(FilesystemLoader $loader, Environment $twig) {
        parent::__construct($loader, $twig);
    }


    /**
     * @brief Permet d'afficher tous les articles (avatars / bannières)
     *
     * @return void
     * @throws LoaderError Exception levée dans le cas d'une erreur de chargement
     * @throws RuntimeError Exception levée dans le cas d'une erreur d'exécution
     * @throws SyntaxError Exception levée dans le cas d'une erreur de syntaxe
     */
    public function show() {
        $managerArticle = new ArticleDAO($this->getPdo());

        $articles = $managerArticle->findAll();
        $pfps = $managerArticle->findAllPfps();
        $banners = $managerArticle->findAllBanners();

        $template = $this->getTwig()->load('shop.twig');

        echo $template->render(array(
            'articles' => $articles,
            'pfps' => $pfps,
            'banners' => $banners
        ));
    }

    /**
     * @brief Permet d'afficher tous les articles
     *
     * @return void
     * @throws LoaderError Exception levée dans le cas d'une erreur de chargement
     * @throws RuntimeError Exception levée dans le cas d'une erreur d'exécution
     * @throws SyntaxError Exception levée dans le cas d'une erreur de syntaxe
     */
    public function showAll(){
        $managerArticle = new ArticleDAO($this->getPdo());
        $articles = $managerArticle->findAll();
        $template = $this->getTwig()->load('shop.twig');

        echo $template->render(array('articles' => $articles));
    }

    /**
     * @brief Permet d'afficher la page de paiement
     *
     * @return void
     * @throws LoaderError Exception levée dans le cas d'une erreur de chargement
     * @throws RuntimeError Exception levée dans le cas d'une erreur d'exécution
     * @throws SyntaxError Exception levée dans le cas d'une erreur de syntaxe
     */
    public  function showCheckout(): void{
        $template = $this->getTwig()->load('checkout.twig');
        echo $template->render();
    }
}