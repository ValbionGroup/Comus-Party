<?php
/**
 * @file    ControllerBasket.class.php
 * @author  Mathis Rivrais--Nowakowski
 * @brief   Fichier de déclaration et définition de la classe ControllerBasket
 * @date    14/11/2024
 * @version 0.4
 */

namespace ComusParty\Controllers;

use ComusParty\App\MessageHandler;
use ComusParty\App\Exceptions\NotFoundException;
use ComusParty\Models\ArticleDAO;
use DateMalformedStringException;
use PHPUnit\Event\Test\AfterLastTestMethodErroredSubscriber;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;


/**
 * @brief Classe ControllerBasket
 * @details La classe ControllerBasket permet de faire le lien entre la vue et l'objet panier
 */
class ControllerBasket extends Controller
{
    /**
     * @brief Constructeur de la classe ControllerBasket
     * @param FilesystemLoader $loader
     * @param Environment $twig
     */
    public function __construct(FilesystemLoader $loader, Environment $twig)
    {
        parent::__construct($loader, $twig);
    }

    /**
     * @brief Affiche le panier avec la liste des articles (si article dans panier)
     * @return void
     * @throws LoaderError Exception levée dans le cas d'une erreur de chargement du template
     * @throws RuntimeError Exception levée dans le cas d'une erreur d'exécution
     * @throws SyntaxError Exception levée dans le cas d'une erreur de syntaxe
     */
    public function show(): void
    {
        $template = $this->getTwig()->load('player/basket.twig');


        $managerArticle = new ArticleDAO($this->getPdo());

        $articles = $managerArticle->findArticlesWithIds($_SESSION['basket']);
        $totalPriceBasket = 0;
        if ($articles) {
            foreach ($articles as $article) {
                $totalPriceBasket += $article->getPriceEuro();
            }
        }

        echo $template->render(
            array(
                'articles' => $articles,
                'totalPriceBasket' => $totalPriceBasket,
            )
        );
    }


    /**
     * @brief Permet d'ajouter un article au panier
     *
     * @return void
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     * @throws LoaderError Exception levée dans le cas d'une erreur de chargement
     * @throws RuntimeError Exception levée dans le cas d'une erreur d'exécution
     * @throws SyntaxError Exception levée dans le cas d'une erreur de syntaxe
     * @throws NotFoundException Exception levée dans le cas d'une erreur de chargement
     */
    function addArticleToBasket(): void
    {
        $managerArticle = new ArticleDAO($this->getPdo());
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

        if (isset($_POST['id_article'])) {
            $id_article = intval($_POST['id_article']);
            if(!in_array($id_article, $idsPfpsOwned) && !in_array($id_article, $idsBannersOwned)){
                if (!isset($_SESSION['basket'])) {
                    $_SESSION['basket'] = array();
                }
                if (!in_array($id_article, $_SESSION['basket'])) {
                    $_SESSION['basket'][] = $id_article;
                    $numberArticlesInBasket = count($_SESSION['basket']);
                    echo MessageHandler::sendJsonMessage("Article ajouté au panier !", ['numberArticlesInBasket' => $numberArticlesInBasket]);
                    exit;
                } else {
                    $numberArticlesInBasket = count($_SESSION['basket']);
                    echo MessageHandler::sendJsonMessage("L'article est déjà dans le panier.", ['numberArticlesInBasket' => $numberArticlesInBasket]);
                    exit;
                }
            }
        } else {
            MessageHandler::sendJsonCustomException(400, "ID de l'article non spécifié.");
        }
    }

    /**
     * @brief Permet de supprimer un article du panier
     * @param $id int ID de l'article à supprimer
     * @return void
     */
    public function removeArticleBasket(int $id): void
    {

        if ($id != null) {
            $id_article = intval($id);

            // Retirer l'ID de l'article au basket s'il n'y est pas déjà
            if (in_array($id_article, $_SESSION['basket'])) {
                $key = array_search($id_article, $_SESSION['basket'], true);
                if ($key !== false) {
                    // Supprimer cette clé
                    unset($_SESSION['basket'][$key]);
                }
                $managerArticle = new ArticleDAO($this->getPdo());
                $article = $managerArticle->findById($id_article);
                $priceEuroArticle = $article->getPriceEuro();
                $numberArticlesInBasket = count($_SESSION['basket']);
                echo MessageHandler::sendJsonMessage("Article supprimé du panier !", [
                    'priceEuroArticle' => $priceEuroArticle,
                    'numberArticlesInBasket' => $numberArticlesInBasket
                ]);
                exit;
            } else {
                MessageHandler::sendJsonCustomException(400, "L'article n'est pas dans le panier.");
            }
        } else {
            MessageHandler::sendJsonCustomException(400, "ID de l'article non spécifié.");
        }
    }


}