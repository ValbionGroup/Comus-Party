<?php

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


}