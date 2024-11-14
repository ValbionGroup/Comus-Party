<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;


/**
 * La classe ControllerAuth est un contrôleur permettant de gérer l'authentification des utilisateurs
 */
class ControllerAuth extends Controller {

    /**
     * Constructeur de la classe ControllerAuth
     *
     * @param FilesystemLoader $loader
     * @param Environment $twig
     */
    public function __construct(FilesystemLoader $loader, Environment $twig) {
        parent::__construct($loader, $twig);
    }

    public function showLoginPage(): void
    {
        global $twig;
        echo $twig->render('login.twig');
    }

    /**
     *  Vérifie si un utilisateur portant le nom d'utilisateur fourni en paramètre existe.
     *  Si oui, vérifie par la suite que le mot de passe fourni corresponde bien au mot de passe inscrit en base de données, puis retourne le résultat
     *
     * @param $email
     * @param $password
     * @return void
     * @throws DateMalformedStringException
     */
    public function authenticate($email, $password): void
    {
        $userManager = new UserDAO($this->getPdo());
        $user = $userManager->findByEmail($email);
        if (is_null($user->getEmailVerifiedAt())) {
            throw new Exception("Merci de vérifier votre adresse e-mail");
        }
        if ($user->getDisabled()) {
            throw new Exception("Votre compte a été désactivé");
        }
        if (password_verify($password, $user->getPassword())) {
            $playerManager = new PlayerDAO($this->getPdo());
            $player = $playerManager->findWithDetailByUserId($user->getId());
            $_SESSION['uuid'] = $player->getUuid();
            $_SESSION['username'] = $player->getUsername();
            $_SESSION['comusCoin'] = $player->getComusCoin();
            $_SESSION['elo'] = $player->getElo();
            $_SESSION['xp'] = $player->getXp();

            $articleManager = new ArticleDAO($this->getPdo());
            $pfp = $articleManager->findActivePfpByPlayerUuid($player->getUuid());
            if (is_null($pfp)) {
                $pfpPath = 'default-pfp.jpg';
            } else {
                $pfpPath = $pfp->getPathImg();
            }
            $_SESSION['pfpPath'] = $pfpPath;
            header('Location: /');
        }
        else {
            throw new Exception("Adresse e-mail ou mot de passe invalide");
        }
    }

    /**
     * Déconnecte l'utilisateur
     *
     * @return void
     */
    public function logOut() : void {
        session_start();
        session_unset();
        session_destroy();
        header('Location: /login');
    }
}