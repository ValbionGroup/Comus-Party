<?php

/**
 * @file    controllerShop.class.php
 * @author  Mathis RIVRAIS--NOWAKOWSKI
 * @brief   Le fichier contient la déclaration & définition de la classe ControllerShop.
 * @date    13/11/2024
 * @version 0.3
 */

namespace ComusParty\Controllers;

use ComusParty\Models\ArticleDAO;
use ComusParty\Models\Exception\PaymentException;
use DateTime;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

/**
 * @brief Classe ControllerShop
 * @details La classe ControllerShop permet de gérer les actions liées à la boutique
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
        if(isset($_SESSION['basket'])){
            $numberArticlesInBasket = count($_SESSION['basket']);
        }else{
            $numberArticlesInBasket = 0;
        }
        echo $template->render(array(
            'articles' => $articles,
            'pfps' => $pfps,
            'banners' => $banners,
            'numberArticlesInBasket' => $numberArticlesInBasket
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
     * - Calcule la clé de Luhn (10 - (somme totale % 10))
     * - Si la clé de Luhn est égale au dernier chiffre du numéro de carte, alors le numéro de carte est valide
     * @param string|null $card Numéro de carte bancaire à vérifier
     * @return bool
     */
    private function checkLuhnValid(?string $card): bool
    {
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
     * @details Les vérifications sont les suivantes :
     *  - Vérification de la longueur du numéro de carte (16 chiffres)
     *  - Vérification de la validité de l'algorithme de Luhn sur le numéro de carte
     *  - Vérification de la longueur du cryptogramme de sécurité (3 chiffres)
     *  - Vérification de la date d'expiration de la carte (date supérieure à la date actuelle)
     * @param array|null $datas Tableau associatif contenant les données du formulaire de paiement
     * @return bool
     * @throws PaymentException Exception levée dans le cas d'une erreur de paiement
     */
    public function checkPaymentRequirement(?array $datas): ?bool
    {
        $cardNumber = preg_replace('/\s/', '', $datas['cardNumber']);

        if (strlen($cardNumber) !== 16) {
            throw new PaymentException("Le numéro de carte doit contenir 16 chiffres");
        }

        if (!$this->checkLuhnValid($cardNumber)) {
            throw new PaymentException("Le numéro de carte n'est pas valide");
        }

        if (strlen($datas['cvv']) !== 3) {
            throw new PaymentException("Le cryptogramme de sécurité doit contenir 3 chiffres");
        }


        list($month, $year) = explode("/", $datas['expirationDate']);
        $expirationDate = new DateTime();
        $expirationDate->setDate(2000 + (int)$year, (int)$month, 1);
        $now = new DateTime();
        if ($expirationDate < $now) {
            throw new PaymentException("La carte a expiré");
        }

        return true;
    }

    public function showInformation($id)
    {
        if ($id != null) {
            $id_article = intval($id);


            $managerArticle = new ArticleDAO($this->getPdo());
            $article = $managerArticle->findById( $id_article );

            echo json_encode([
                'success' => true,
                'type' => $article->getType()
            ]);
        } else {
            echo "Erreur : ID de l'article non spécifié.";
        }
    }


}