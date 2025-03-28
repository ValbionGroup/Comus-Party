<?php

/**
 * @file    ControllerShop.class.php
 * @author  Mathis RIVRAIS--NOWAKOWSKI
 * @brief   Fichier de déclaration et définition de la classe ControllerShop
 * @date    13/11/2024
 * @version 0.3
 */

namespace ComusParty\Controllers;

use ComusParty\App\Exceptions\NotFoundException;
use ComusParty\App\Mailer;
use ComusParty\App\MessageHandler;
use ComusParty\Models\ArticleDAO;
use ComusParty\Models\InvoiceDAO;
use ComusParty\Models\PlayerDAO;
use ComusParty\Models\UserDAO;
use DateMalformedStringException;
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
class ControllerShop extends Controller
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
     * @brief Permet d'afficher tous les articles (avatars / bannières)
     *
     * @return void
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     * @throws LoaderError Exception levée dans le cas d'une erreur de chargement
     * @throws RuntimeError Exception levée dans le cas d'une erreur d'exécution
     * @throws SyntaxError Exception levée dans le cas d'une erreur de syntaxe
     * @throws NotFoundException Exception levée dans le cas d'une erreur de non-trouvabilité
     */
    public function show()
    {
        $managerArticle = new ArticleDAO($this->getPdo());
        $articles = $managerArticle->findAll();
        $pfps = $managerArticle->findAllPfps();
        $banners = $managerArticle->findAllBanners();
        $pfpsOwned = $managerArticle->findAllPfpsOwnedByPlayer($_SESSION['uuid']);
        $idsPfpsOwned = [];
        foreach ($pfpsOwned as $pfpOwned) {
            $idsPfpsOwned[] = $pfpOwned->getId();
        }
        $bannersOwned = $managerArticle->findAllBannersOwnedByPlayer($_SESSION['uuid']);
        $idsBannersOwned = [];
        foreach ($bannersOwned as $bannerOwned) {
            $idsBannersOwned[] = $bannerOwned->getId();
        }
        $template = $this->getTwig()->load('player/shop.twig');
        if (isset($_SESSION['basket'])) {
            $numberArticlesInBasket = count($_SESSION['basket']);
        } else {
            $numberArticlesInBasket = 0;
        }
        echo $template->render(array(
            'articles' => $articles,
            'pfps' => $pfps,
            'banners' => $banners,
            'idsPfpsOwned' => $idsPfpsOwned,
            'idsBannersOwned' => $idsBannersOwned,
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
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     */
    public function showAll()
    {
        $managerArticle = new ArticleDAO($this->getPdo());
        $articles = $managerArticle->findAll();
        $template = $this->getTwig()->load('player/shop.twig');

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
    public function showCheckout()
    {
        $articles = [];
        foreach ($_SESSION['basket'] as $id) {
            $managerArticle = new ArticleDAO($this->getPdo());
            $article = $managerArticle->findById($id);
            $articles[] = $article;
        }

        $template = $this->getTwig()->load('player/checkout.twig');
        echo $template->render(array('articles' => $articles));
    }

    /**
     * @brief Vérifie si l'ensemble des données du formulaire de paiement, passées en paramètre via un tableau associatif sont valides.
     * @details Les vérifications sont les suivantes :
     *  - Vérification de la longueur du numéro de carte (16 chiffres)
     *  - Vérification de la validité de l'algorithme de Luhn sur le numéro de carte
     *  - Vérification de la longueur du cryptogramme de sécurité (3 chiffres)
     *  - Vérification de la date d'expiration de la carte (date supérieure à la date actuelle)
     * @param array|null $datas Tableau associatif contenant les données du formulaire de paiement
     * @return bool|null
     */
    public function checkPaymentRequirement(?array $datas): ?bool
    {
        $cardNumber = preg_replace('/\s/', '', $datas['cardNumber']);

        if (strlen($cardNumber) !== 16) {
            MessageHandler::sendJsonCustomException(400, "Le numéro de la carte doit contenir 16 chiffres.");
            return false;
        }

        if (!$this->checkLuhnValid($cardNumber)) {
            MessageHandler::sendJsonCustomException(400, "Le numéro de carte n'est pas valide.");
            return false;
        }

        if (strlen($datas['cvv']) !== 3) {
            MessageHandler::sendJsonCustomException(400, "Le cryptogramme de sécurité doit contenir 3 chiffres.");
            return false;
        }


        list($month, $year) = explode("/", $datas['expirationDate']);
        $expirationDate = new DateTime();
        $expirationDate->setDate(2000 + (int)$year, (int)$month, 1);
        $now = new DateTime();
        if ($expirationDate < $now) {
            MessageHandler::sendJsonCustomException(400, "La date d'expiration de la carte est dépassée.");
            return false;
        }

        echo MessageHandler::sendJsonMessage(200, "Paiement effectué avec succès.");
        return true;
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
     * @brief Affiche la facture générée grâce à l'ID passé en paramètre GET
     * @param int $invoiceId L'ID de la facture à afficher
     * @return void
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     * @throws LoaderError Exception levée dans le cas d'une erreur de chargement
     * @throws NotFoundException
     * @throws RuntimeError Exception levée dans le cas d'une erreur d'exécution
     * @throws SyntaxError Exception levée dans le cas d'une erreur de syntaxe
     */
    public function showInvoice(int $invoiceId)
    {
        $managerArticle = new ArticleDAO($this->getPdo());
        $managerPlayer = new PlayerDAO($this->getPdo());
        $managerUser = new UserDAO($this->getPdo());
        $managerInvoice = new InvoiceDAO($this->getPdo());

        $articles = $managerArticle->findArticlesByInvoiceId($invoiceId);
        $player = $managerPlayer->findWithDetailByUuid($_SESSION['uuid']);
        $email = $managerUser->findById($player->getUserId())->getEmail();
        $invoice = $managerInvoice->findById($invoiceId);

        $template = $this->getTwig()->load('player/invoice.twig');
        echo $template->render(array(
            'invoice' => $invoice,
            'username' => $player->getUsername(),
            'email' => $email,
            'articles' => $articles
        ));
    }

    /**
     * @brief Affiche la page de succès de paiement
     * @param array $articles Tableau d'articles achetés
     * @param string $playerUuid UUID du joueur ayant effectué l'achat
     * @param string $paymentType Type de paiement effectué
     * @return void
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     * @throws LoaderError Exception levée dans le cas d'une erreur de chargement
     * @throws RuntimeError Exception levée dans le cas d'une erreur d'exécution
     * @throws SyntaxError Exception levée dans le cas d'une erreur de syntaxe
     */
    public function showSuccessPayment(array $articles, string $playerUuid, string $paymentType): void
    {
        $managerInvoice = new InvoiceDAO($this->getPdo());
        $managerInvoice->createInvoiceWithArticles($playerUuid, $paymentType, $articles);

        $managerPlayer = new PlayerDAO($this->getPdo());
        $player = $managerPlayer->findByUuid($playerUuid);
        $managerUser = new UserDAO($this->getPdo());
        $user = $managerUser->findById($player->getUserId());

        $mail = new Mailer(array($user->getEmail()), "ComusParty - Paiement effectué", "Votre paiement a bien été effectué. Vous pouvez consulter votre facture sur votre profil.");
        $mail->send();

        $template = $this->getTwig()->load('player/success-payment.twig');
        echo $template->render();

        unset($_SESSION['basket']);
        header("Refresh: 5; url=/");
        exit();
    }
}