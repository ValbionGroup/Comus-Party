<?php

class ControllerPlayer extends Controller {
    public function __construct(\Twig\Loader\FilesystemLoader $loader, \Twig\Environment $twig) {
        parent::__construct($loader, $twig);
    }

    public function afficher() {
        if (!isset($_GET['uuid'])) {
            header('Location: ../pages/404.php');
            exit();
        }
        $player_uuid = $_GET['uuid'];
        $playerManager = new PlayerDAO($this->getPdo());
        $userManager = new UserDAO($this->getPdo());
        $player = $playerManager->findWithDetailByUuid($player_uuid);
        if ($player === null) {
            header('Location: ../pages/404.php');
            exit();
        }
        $user = $userManager->findById($player->getUserId());
        $template = $this->getTwig()->load('profil.twig');
        echo $template->render(array(
            "player" => $player,
            "user" => $user
        ));
    }
}