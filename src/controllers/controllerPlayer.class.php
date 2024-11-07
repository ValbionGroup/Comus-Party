<?php

use models\RouteNotFoundException;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ControllerPlayer extends Controller {
    public function __construct(FilesystemLoader $loader, Environment $twig) {
        parent::__construct($loader, $twig);
    }

    public function afficher() {
        if (!isset($_GET['uuid'])) {
            throw new RouteNotFoundException('Player not found');
        }
        $player_uuid = $_GET['uuid'];
        $playerManager = new PlayerDAO($this->getPdo());
        $userManager = new UserDAO($this->getPdo());
        $player = $playerManager->findWithDetailByUuid($player_uuid);
        if ($player === null) {
            throw new RouteNotFoundException('Player not found');
        }
        $user = $userManager->findById($player->getUserId());
        $template = $this->getTwig()->load('profil.twig');
        echo $template->render(array(
            "player" => $player,
            "user" => $user
        ));
    }
}