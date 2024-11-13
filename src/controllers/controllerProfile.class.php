<?php

use models\NotFoundException;
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
     * @brief Constructeur de la classe ControllerProfile
     * @param FilesystemLoader $loader Le loader de Twig
     * @param Environment $twig L'environnement de Twig
     */
    public function __construct(FilesystemLoader $loader, Environment $twig) {
        parent::__construct($loader, $twig);
    }

    /**
     * @brief Affiche le profil du joueur le demandant
     * @return void
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     * @throws LoaderError Exception levée dans le cas d'une erreur de chargement
     * @throws RuntimeError Exception levée dans le cas d'une erreur d'exécution
     * @throws NotFoundException|SyntaxError Exception levée dans le cas d'une erreur de syntaxe
     */
    public function showByPlayer(?string $player_uuid): void
    {
        if (is_null($player_uuid)) {
            throw new NotFoundException('Player not found');
        }
        $playerManager = new PlayerDAO($this->getPdo());
        $userManager = new UserDAO($this->getPdo());
        $player = $playerManager->findWithDetailByUuid($player_uuid);
        if (is_null($player)) {
            throw new NotFoundException('Player not found');
        }
        $user = $userManager->findById($player->getUserId());
        $template = $this->getTwig()->load('profil.twig');
        echo $template->render(array(
            "player" => $player,
            "user" => $user
        ));
    }
}