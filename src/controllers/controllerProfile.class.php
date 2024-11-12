<?php

use models\RouteNotFoundException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

/**
 * Contrôleur de la page profile, utilisé pour afficher le profil d'un joueur sous différents angles
 */
class ControllerProfile extends Controller {

    /**
     * Constructeur de la classe ControllerProfile
     * @param FilesystemLoader $loader
     * @param Environment $twig
     */
    public function __construct(FilesystemLoader $loader, Environment $twig) {
        parent::__construct($loader, $twig);
    }

    /**
     * Affiche la page de profil du joueur connecté
     *
     * @return void
     * @throws DateMalformedStringException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError|RouteNotFoundException
     */
    public function showByPlayer(): void
    {
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