<?php
/**
 * @file    controllerProfile.class.php
 * @brief   Ce fichier contient la déclaration & définition de la classe ControllerProfile.
 * @author  Estéban DESESSARD
 * @date    15/11/2024
 * @version 0.2
 */

namespace ComusParty\Controllers;

use ComusParty\Models\ArticleDAO;
use ComusParty\Models\Exception\NotFoundException;
use ComusParty\Models\PlayerDAO;
use ComusParty\Models\UserDAO;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

/**
 * @brief Classe ControllerProfile
 * @details Contrôleur de la page profile, utilisé pour afficher le profil d'un joueur sous différents angles (vu par lui-même, par un autre joueur, ou par un modérateur)
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
        $articleManager = new ArticleDAO($this->getPdo());
        $pfp = $articleManager->findActivePfpByPlayerUuid($player->getUuid());
        if (is_null($pfp)) {
            $pfpPath = 'default-pfp.jpg';
        } else {
            $pfpPath = $pfp->getPathImg();
        }
        $banner = $articleManager->findActiveBannerByPlayerUuid($player->getUuid());
        if (is_null($banner)) {
            $bannerPath = 'default-banner.jpg';
        } else {
            $bannerPath = $banner->getPathImg();
        }
        $template = $this->getTwig()->load('profil.twig');
        echo $template->render(array(
            "player" => $player,
            "user" => $user,
            "pfp" => $pfpPath,
            "banner" => $bannerPath
        ));
    }
}