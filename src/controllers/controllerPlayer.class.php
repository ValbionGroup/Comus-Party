<?php

class ControllerPlayer extends Controller {
    public function __construct(\Twig\Loader\FilesystemLoader $loader, \Twig\Environment $twig) {
        parent::__construct($loader, $twig);
    }

    public function viewByPlayer() {
        $player_uuid = $_GET['uuid'];
        $playerManager = new PlayerDAO($this->getPdo());
        $player = $playerManager->findWithDetailByUuid($player_uuid);
        $template = $this->getTwig()->load('profil.twig');
        echo $template->render(array(
            "player" => $player
        ));
    }
}