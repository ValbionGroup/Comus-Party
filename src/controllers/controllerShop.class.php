<?php

/**
 * @file    controllerShop.class.php
 * @author  Mathis RIVRAIS--NOWAKOWSKI
 * @brief   Le fichier contient la déclaration & définition de la classe ControllerShop.
 * @date    13/11/2024
 * @version 0.1
 */


use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

/**
 * @brief Classe ControllerShop
 * @details La classe ControllerShop permet de gérer les actions liées à un article
 * @extends Controller
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
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
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
     * @brief Permet d'ajouter un article au panier
     *
     * @return void
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     * @throws LoaderError Exception levée dans le cas d'une erreur de chargement
     * @throws RuntimeError Exception levée dans le cas d'une erreur d'exécution
     * @throws SyntaxError Exception levée dans le cas d'une erreur de syntaxe
     */
    function addBasket()
    {
// Vérifier si l'ID de l'article a été envoyé

        if (isset($_POST['id_article'])) {
            $id_article = intval($_POST['id_article']);

            // Initialiser le panier s'il n'existe pas
            if (!isset($_SESSION['basket'])) {
                $_SESSION['basket'] = [];
            }

            // Ajouter l'ID de l'article au panier s'il n'y est pas déjà
            if (!in_array($id_article, $_SESSION['basket'])) {
                $_SESSION['basket'][] = $id_article;


                echo json_encode([
                    'success' => true,
                    'message' => "Article ajouté au panier !"
                ]);

            } else {
                echo json_encode([
                    'success' => false,
                    'message' => "L'article est déjà dans le panier."
                ]);

            }
        } else {
            echo "Erreur : ID de l'article non spécifié.";
        }
    }


    /**
     * @brief Permet d'afficher tous les articles
     *
     * @return void
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
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
}