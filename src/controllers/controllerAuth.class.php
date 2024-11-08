<?php

require_once __DIR__ . '/../../include.php';

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
    public function authenticate($email, $password) {
        $userManager = new UserDAO($this->getPdo());
        $user = $userManager->findByEmail($email);
        if ($user === null) {
            $template = $this->getTwig()->load('login.twig');
            echo $template->render(array(
                "error" => "Email not found"
            ));
            return;
        }
        if (!password_verify($_POST['password'], $user->getPassword())) {
            $template = $this->getTwig()->load('login.twig');
            echo $template->render(array(
                "error" => "Password incorrect"
            ));
            return;
        }
        $_SESSION['user'] = $user;
        header('Location: /');
    }

    /**
     * @return void
     */
    public function logOut() {
        session_destroy();
        header('Location: /');
    }
}