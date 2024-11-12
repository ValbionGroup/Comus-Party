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
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function authenticate($email, $password) : ?string {
        $userManager = new UserDAO($this->getPdo());
        $user = $userManager->findByEmail($email);
        if (is_null($user->getEmailVerifiedAt())) {
            echo "Merci de vérifier votre adresse e-mail";
            return;
        }
        if ($user && ($password === password_verify($password, $user->getPassword()))) {
            session_start();
            $_SESSION['user'] = $user;
            header('Location: /');
        }
        else {
            echo "Adresse e-mail ou mot de passe invalide";
        }
    }

    /**
     * Déconnecte l'utilisateur
     *
     * @return void
     */
    public function logOut() : void {
        session_destroy();
        header('Location: /login');
    }
}