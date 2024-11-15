<?php
/**
 * @file    controllerBasket.class.php
 * @author  Mathis Rivrais--Nowakowski
 * @brief   Le fichier contient la déclaration & définition de la classe ControllerBasket.
 * @date    14/11/2024
 * @version 0.4
 */

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * La classe ControllerBasket permet de faire le lien entre la vue et l'objet Basket
 */
class ControllerBasket extends Controller
{
    /**
     * Constructeur de la classe ControllerBasket
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
    public function show(){
        $template = $this->getTwig()->load('basket.twig');


        $managerArticle = new ArticleDAO($this->getPdo());

        $articles = $managerArticle->findArticlesWithIds($_SESSION['basket']);

        echo $template->render(
            array('articles' => $articles)
        );
    }
    /**
     * @brief Permet de supprimer un article au panier
     *
     * @return void
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     * @throws LoaderError Exception levée dans le cas d'une erreur de chargement
     * @throws RuntimeError Exception levée dans le cas d'une erreur d'exécution
     * @throws SyntaxError Exception levée dans le cas d'une erreur de syntaxe
     */
    public function removeArticleBasket($id){

        if ($id != null) {
            $id_article = intval($id);



            // Ajouter l'ID de l'article au panier s'il n'y est pas déjà
            if (in_array($id_article, $_SESSION['basket'])) {
                var_dump($_SESSION['basket']);

                $key = array_search($id_article, $_SESSION['basket'], true);
                var_dump($key);
                if ($key !== false) {
                    // Supprimer cette clé
                    unset($_SESSION['basket'][$key]);
                }
                echo "Article supprimé du panier !";
            } else {
                echo "L'article n'est pas dans le panier.";
            }
        } else {
            echo "Erreur : ID de l'article non spécifié.";
        }
    }


}