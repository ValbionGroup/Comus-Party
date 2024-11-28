<?php
/**
 * @brief Fichier de définition des routes
 *
 * @file routes.php
 * @author Lucas ESPIET "lespiet@iutbayonne.univ-pau.fr"
 * @version 1.0
 * @date 2024-11-12
 */

global $loader, $twig;

use ComusParty\Controllers\ControllerFactory;
use ComusParty\Models\Router;

$router = Router::getInstance();

$router->get('/', function () use ($loader, $twig) {
    if (!isset($_SESSION['uuid'])) {
        header('Location: /login');
        exit;
    }
    ControllerFactory::getController("game",$loader,$twig)->call("show");
    exit;
});

// Route pour afficher le profil
$router->get('/profile', function () use ($loader, $twig) {
    if (!isset($_SESSION['uuid'])) {
        header('Location: /login');
        exit;
    }
    ControllerFactory::getController("profile", $loader, $twig)->call("showByPlayer", [
        "playerUuid" => $_SESSION["uuid"]
    ]);
    exit;
});

// Route pour afficher le formulaire de connexion
$router->get('/login', function () use ($loader, $twig) {
    if (isset($_SESSION['uuid'])) {
        header('Location: /');
        exit;
    }
    ControllerFactory::getController("auth", $loader, $twig)->call("showLoginPage");
    exit;
});

// Route pour traiter la soumission du formulaire de connexion
$router->post('/login', function () use ($loader, $twig) {
    if (isset($_SESSION['uuid'])) {
        header('Location: /');
        exit;
    }
    try {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            ControllerFactory::getController("auth", $loader, $twig)->call("authenticate", [
                "email" => $_POST['email'],
                "password" => $_POST['password']
            ]);
            exit;
        }
        throw new Exception("Merci de renseigner une adresse e-mail et un mot de passe valides");
    } catch (Exception $e) {
        displayPopUpError($e);
        ControllerFactory::getController("auth", $loader, $twig)->call("showLoginPage");
    }
});

$router->get('/logout', function () use ($loader, $twig) {
    if (!isset($_SESSION['uuid'])) {
        header('Location: /login');
        exit;
    }
    ControllerFactory::getController("auth", $loader, $twig)->call("logout");
    exit;
});

$router->get('/game/:code', function ($code) {
    echo "Page de jeu en cours : " . $code . "<br/>";
    echo "A IMPLEMENTER";
    exit;
});

$router->get('/shop', function () use ($loader, $twig) {
    if (!isset($_SESSION['uuid'])) {
        header('Location: /login');
        exit;
    }
    ControllerFactory::getController("shop", $loader, $twig)->call("show");
    exit;
});

$router->get('/shop/basket', function () {
    if (!isset($_SESSION['uuid'])) {
        header('Location: /login');
        exit;
    }
    echo "Page du panier<br/>";
    echo "A IMPLEMENTER";
    exit;
});

$router->post('/shop/basket/add/:id', function ($id) {
    if (!isset($_SESSION['uuid'])) {
        header('Location: /login');
        exit;
    }
    echo "Ajout d'un article au panier : " . $id . "<br/>";
    echo "A IMPLEMENTER";
    exit;
});

$router->delete('/shop/basket/remove/:id', function ($id) {
    if (!isset($_SESSION['uuid'])) {
        header('Location: /login');
        exit;
    }
    echo "Suppression d'un article du panier : " . $id . "<br/>";
    echo "A IMPLEMENTER";
    exit;
});

$router->get('/shop/basket/checkout', function () use($loader, $twig) {
    if (!isset($_SESSION['uuid'])) {
        header('Location: /login');
        exit;
    }
    ControllerFactory::getController("shop", $loader, $twig)->call("showCheckout");
    exit;
});

$router->post('/shop/basket/checkout', function () {
    if (!isset($_SESSION['uuid'])) {
        header('Location: /login');
        exit;
    }
    echo "Traitement du paiement<br/>";
    echo "A IMPLEMENTER";
    exit;
});

$router->get('/register', function () {
    echo "Page d'inscription<br/>";
    echo "A IMPLEMENTER";
    exit;
});

$router->post('/register', function () {
    echo "Traitement de l'inscription<br/>";
    echo "A IMPLEMENTER";
    exit;
});

$router->get('/profile/view/:uuid', function ($uuid) use ($loader, $twig) {
    if (!isset($_SESSION['uuid'])) {
        header('Location: /login');
        exit;
    }
    ControllerFactory::getController("profile", $loader, $twig)->call("showByPlayer", ["playerUuid" => $uuid]);
    exit;
});

$router->put('/profile', function () {
    if (!isset($_SESSION['uuid'])) {
        header('Location: /login');
        exit;
    }
    echo "Mise à jour du profil<br/>";
    echo "A IMPLEMENTER";
    exit;
});