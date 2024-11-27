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
     * @brief Exécute l'algorithme de Luhn sur le numéro de carte passé en paramètre
     * @details L'algorithme de Luhn parcourt le numéro de carte de la manière suivante :
     * - Multiplie par 2 chaque chiffre en position paire (en partant de 0)
     * - Si le résultat de la multiplication est supérieur ou égal à 10, additionne les chiffres du résultat et ajoute le résultat à la somme totale
     * - Ajoute les chiffres en position impaire à la somme totale
     * @return bool
     */
    private function checkLuhnValid(?string $card): bool
    {
        $card = preg_replace('/\s/', '', $card);
        $sum = 0;
        $length = strlen($card);

        for ($i = 0; $i < $length - 1; $i++) {
            if ($i % 2 == 0) {
                $digit = (int)$card[$i] * 2;
                if ($digit >= 10) {
                    $digitStr = (string)$digit;
                    $digit = (int)$digitStr[0] + (int)$digitStr[1];
                }
                $sum += $digit;
            } else {
                $sum += (int)$card[$i];
            }
        }
        $key = 10 - ($sum % 10) % 10;
        return $key == $card[$length - 1];
    }

    /**
     * @brief Vérifie si l'ensemble des données du formulaire de paiement, passées en paramètre via un tableau associatif sont valides.
     * @param array|null $datas
     * @return bool
     */
    public function checkPaymentRequirement(?array $datas): bool
    {
        var_dump($this->checkLuhnValid("49900109835851023"));
        return true;
    }
}