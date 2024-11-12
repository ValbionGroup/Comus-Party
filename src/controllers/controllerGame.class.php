<?php
/**
 * La classe ControllerGame permet de faire le lien entre la vue et l'objet Game
 */
class ControllerGame extends Controller
{
    /**
     * Constructeur de la classe ControllerGame
     * @param \Twig\Loader\FilesystemLoader $loader
     * @param \Twig\Environment $twig
     */
    public function __construct(\Twig\Loader\FilesystemLoader $loader, \Twig\Environment $twig)
    {
        parent::__construct($loader, $twig);
    }

    public function show(){
        $gameManager = new GameDAO($this->getPdo());
        $games = $gameManager->findAll();
        $template = $this->getTwig()->load('home.twig');
        echo $template->render(array(
            "games" => $games
        ));
    }
}