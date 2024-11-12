<?php
/**
 * @file    controllerPlayer.class.php
 * @author  Estéban DESESSARD
 * @brief   Le fichier contient la déclaration & définition de la classe ControllerPlayer.
 * @date    12/11/2024
 * @version 0.1
 */

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

/**
 * @brief   La classe ControllerPlayer permet de gérer les actions liées à un joueur
 */
class ControllerPlayer extends Controller {
    /**
     * @brief                           Constructeur de la classe ControllerPlayer
     * @param FilesystemLoader $loader Le loader de Twig
     * @param Environment $twig L'environnement de Twig
     */
    public function __construct(FilesystemLoader $loader, Environment $twig)
    {
        parent::__construct($loader, $twig);
    }

    /**
     * @brief                               La méthode afficher permet d'afficher le profil d'un joueur
     * @return void                         Objet retourné par la méthode, ici rien
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     * @throws LoaderError                  Exception levée dans le cas d'une erreur de chargement
     * @throws RuntimeError                 Exception levée dans le cas d'une erreur d'exécution
     * @throws SyntaxError                  Exception levée dans le cas d'une erreur de syntaxe
     */
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