<?php
/**
 * La classe ControllerGame permet de faire le lien entre la vue et l'objet Game
 */
class ControllerGame extends Controller
{
    public function __construct(\Twig\Loader\FilesystemLoader $loader, \Twig\Environment $twig)
    {
        parent::__construct($loader, $twig);
    }

    public function show(){
        $gameManager = new GameDAO($this->getPdo());
        $games = $gameManager->findAll();
        $template = $this->getTwig()->load('accueil.twig');
        echo $template->render(array(
            "games" => $games
        ));
    }
}