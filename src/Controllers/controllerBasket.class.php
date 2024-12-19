<?php
/**
 * @file    controllerBasket.class.php
 * @author  Mathis Rivrais--Nowakowski
 * @brief   Le fichier contient la déclaration & définition de la classe ControllerBasket.
 * @date    14/11/2024
 * @version 0.4
 */

namespace ComusParty\Controllers;

use ComusParty\Controllers\Controller;
use ComusParty\Models\ArticleDAO;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


/**
 * La classe ControllerBasket permet de faire le lien entre la vue et l'objet Basket
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
     * @brief Affiche le basket avec la liste des articles (si article dans basket)
     * @return void
     * @throws LoaderError Exception levée dans le cas d'une erreur de chargement du template
     * @throws RuntimeError Exception levée dans le cas d'une erreur d'exécution
     * @throws SyntaxError Exception levée dans le cas d'une erreur de syntaxe
     */
    public function show(){
        $template = $this->getTwig()->load('basket.twig');


        $managerArticle = new ArticleDAO($this->getPdo());

        $articles = $managerArticle->findArticlesWithIds($_SESSION['basket']);
        $totalPriceBasket = 0;
        if($articles){
            foreach($articles as $article){
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
     * @brief Permet d'ajouter un article au basket
     *
     * @return void
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     * @throws LoaderError Exception levée dans le cas d'une erreur de chargement
     * @throws RuntimeError Exception levée dans le cas d'une erreur d'exécution
     * @throws SyntaxError Exception levée dans le cas d'une erreur de syntaxe
     */
    function addArticleToBasket()
    {
// Vérifier si l'ID de l'article a été envoyé

        if (isset($_POST['id_article'])) {
            $id_article = intval($_POST['id_article']);


            // Ajouter l'ID de l'article au basket s'il n'y est pas déjà
            if (!in_array($id_article, $_SESSION['basket'])) {
                $_SESSION['basket'][] = $id_article;
                $numberArticlesInBasket = count($_SESSION['basket']);


                echo json_encode([
                    'success' => true,
                    'message' => "Article ajouté au basket !",
                    'numberArticlesInBasket' => $numberArticlesInBasket
                ]);

            } else {
                $numberArticlesInBasket = count($_SESSION['basket']);
                echo json_encode([
                    'success' => false,
                    'message' => "L'article est déjà dans le basket.",
                    'numberArticlesInBasket' => $numberArticlesInBasket
                ]);

            }
        } else {
            echo "Erreur : ID de l'article non spécifié.";
        }
    }




    /**
     * @brief Permet de supprimer un article du basket
     * @return void
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     * @throws LoaderError Exception levée dans le cas d'une erreur de chargement
     * @throws RuntimeError Exception levée dans le cas d'une erreur d'exécution
     * @throws SyntaxError Exception levée dans le cas d'une erreur de syntaxe
     */
    public function removeArticleBasket($id){

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
                $article = $managerArticle->findById( $id_article );
                $priceEuroArticle = $article->getPriceEuro();
                $numberArticlesInBasket = count($_SESSION['basket']);
                echo json_encode([
                    'success' => true,
                    'message' => "Article supprimé du basket !",
                    'priceEuroArticle' => $priceEuroArticle,
                    'numberArticlesInBasket' => $numberArticlesInBasket

                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => "L'article n'est pas dans le basket."
                ]);

            }
        } else {
            echo "Erreur : ID de l'article non spécifié.";
        }
    }


}