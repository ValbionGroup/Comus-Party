<?php

/**
 * Contrôleur  de la boutique
 *
 */
class ControllerShop extends Controller {
    /**
     * @param \Twig\Loader\FilesystemLoader $loader
     * @param \Twig\Environment $twig
     */
    public function __construct(\Twig\Loader\FilesystemLoader $loader, \Twig\Environment $twig) {
        parent::__construct($loader, $twig);
    }


    /**
     * Permet d'afficher tous les articles (avatars / bannières)
     *
     * @return void
     * @throws DateMalformedStringException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
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
     * Permet d'afficher tous les articles
     *
     * @return void
     * @throws DateMalformedStringException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function showAll(){
        $managerArticle = new ArticleDAO($this->getPdo());

        $articles = $managerArticle->findAll();

        $template = $this->getTwig()->load('shop.twig');

        echo $template->render(array('articles' => $articles));
    }
}