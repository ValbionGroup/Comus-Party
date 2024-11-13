<?php
/**
 * La classe ControllerBasket permet de faire le lien entre la vue et l'objet Basket
 */
class ControllerBasket extends Controller
{
    /**
     * Constructeur de la classe ControllerBasket
     * @param \Twig\Loader\FilesystemLoader $loader
     * @param \Twig\Environment $twig
     */
    public function __construct(\Twig\Loader\FilesystemLoader $loader, \Twig\Environment $twig)
    {
        parent::__construct($loader, $twig);
    }

    public function show(){
//        $gameManager = new GameDAO($this->getPdo());
//        $games = $gameManager->findAll();
        $template = $this->getTwig()->load('basket.twig');
        echo $template->render();
    }
}