<?php

/**
 * Contrôleur de la page profile, utilisé pour afficher le profil d'un joueur sous différents angles
 */
class ControllerProfile extends Controller {

    /**
     * Constructeur de la classe ControllerProfile
     * @param \Twig\Loader\FilesystemLoader $loader
     * @param \Twig\Environment $twig
     */
    public function __construct(\Twig\Loader\FilesystemLoader $loader, \Twig\Environment $twig) {
        parent::__construct($loader, $twig);
    }

    /**
     * Affiche la page de profil du joueur connecté
     *
     * @return void
     * @throws DateMalformedStringException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function showByPlayer() {
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