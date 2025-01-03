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

use ComusParty\App\Exception\UnauthorizedAccessException;
use ComusParty\App\MessageHandler;
use ComusParty\App\Router;
use ComusParty\Controllers\ControllerFactory;

$router = Router::getInstance();

$router->get('/', function () use ($loader, $twig) {
    ControllerFactory::getController("game", $loader, $twig)->call("showHomePage");
    exit;
}, 'player');

// Route pour afficher le profil
$router->get('/profile', function () use ($loader, $twig) {
    ControllerFactory::getController("profile", $loader, $twig)->call("showByPlayer", [
        "playerUuid" => $_SESSION["uuid"]
    ]);
    exit;
}, 'player');

$router->post('/profile/updateStyle/:idArticle', function ($idArticle) use ($loader, $twig) {

    ControllerFactory::getController("profile", $loader, $twig)->call("updateStyle", [
        "uuidPlayer" => $_SESSION["uuid"],
        "idArticle" => $idArticle
    ]);
    exit;
}, 'player');

// Route pour afficher le formulaire de connexion
$router->get('/login', function () use ($loader, $twig) {
    ControllerFactory::getController("auth", $loader, $twig)->call("showLoginPage");
    exit;
}, 'guest');

// Route pour traiter la soumission du formulaire de connexion
$router->post('/login', function () use ($loader, $twig) {
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
        MessageHandler::addExceptionParametersToSession($e);
        ControllerFactory::getController("auth", $loader, $twig)->call("showLoginPage");
    }
}, 'guest');

$router->get('/logout', function () use ($loader, $twig) {
    ControllerFactory::getController("auth", $loader, $twig)->call("logout");
    exit;
}, 'auth');

$router->get('/game/:code', function ($code) use ($loader, $twig) {
    ControllerFactory::getController("game", $loader, $twig)->call("showGame", ["code" => $code]);
}, 'player');

$router->delete('/game/:code/quit', function ($code) use ($loader, $twig) {
    ControllerFactory::getController("game", $loader, $twig)->call("quitGame", [
        "code" => $code,
        "playerUuid" => $_SESSION['uuid']
    ]);
    exit;
}, 'player');

$router->post('/game/create/:gameId', function (int $gameId) use ($loader, $twig) {
    ControllerFactory::getController("game", $loader, $twig)->call("createGame", [
        "gameId" => $gameId,
    ]);
}, 'player');

$router->get('/shop', function () use ($loader, $twig) {
    ControllerFactory::getController("shop", $loader, $twig)->call("show");
    exit;
}, 'player');

$router->get('/shop/basket', function () use ($loader, $twig) {
    ControllerFactory::getController("basket", $loader, $twig)->call("show");
    exit;
}, 'player');

$router->post('/shop/basket/add', function () use ($loader, $twig) {
    ControllerFactory::getController("basket", $loader, $twig)->call("addArticleToBasket");
    exit;
}, 'player');

$router->delete('/shop/basket/remove/:id', function ($id) use ($loader, $twig) {

    ControllerFactory::getController("basket", $loader, $twig)->call("removeArticleBasket", ["id" => $id]);
    exit;
}, 'player');

$router->get('/shop/basket/checkout', function () use ($loader, $twig) {
    if (empty($_SESSION['basket'])) {
        MessageHandler::addExceptionParametersToSession(new UnauthorizedAccessException("Votre panier est vide"));
        header('Location: /shop');
        exit;
    }
    ControllerFactory::getController("shop", $loader, $twig)->call("showCheckout");
    exit;
}, 'player');

$router->post('/shop/basket/checkout', function () {
    exit;
}, 'player');

$router->post('/shop/basket/checkout/confirm', function () use ($loader, $twig) {
    ControllerFactory::getController("shop", $loader, $twig)->call("checkPaymentRequirement", array($_POST));
    exit;
}, 'player');

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

$router->get('/forgot-password', function () use ($loader, $twig) {
    ControllerFactory::getController("auth", $loader, $twig)->call("showForgotPasswordPage");
    exit;
}, 'guest');

$router->post('/forgot-password', function () use ($loader, $twig) {
    ControllerFactory::getController("auth", $loader, $twig)->call("sendResetPasswordLink", ["email" => $_POST['email']]);
    exit;
}, 'guest');

$router->get('/reset-password/:token', function (string $token) use ($loader, $twig) {
    ControllerFactory::getController("auth", $loader, $twig)->call("showResetPasswordPage", ["token" => $token]);
    exit;
}, 'guest');

$router->post('/reset-password/:token', function (string $token) use ($loader, $twig) {
    ControllerFactory::getController("auth", $loader, $twig)->call("resetPassword", [
        "token" => $token,
        "password" => $_POST['password'],
        "passwordConfirm" => $_POST['passwordConfirm']
    ]);
    exit;
}, 'guest');

$router->get('/profile/view/:uuid', function ($uuid) use ($loader, $twig) {
    ControllerFactory::getController("profile", $loader, $twig)->call("showByPlayer", ["playerUuid" => $uuid]);
    exit;
}, 'player');

$router->put('/profile', function () {
    echo "Mise à jour du profil<br/>";
    echo "A IMPLEMENTER";
    exit;
});

$router->get('/invoice/:id', function ($id) use ($loader, $twig) {
    ControllerFactory::getController("shop", $loader, $twig)->call("showInvoice", ["invoiceId" => $id]);
}, 'player');

$router->get('/disable-account/:uuid', function ($uuid) use ($loader, $twig) {
    ControllerFactory::getController("profile", $loader, $twig)->call("disableAccount", ["uuid" => $uuid]);
    exit;
}, 'player');

$router->post('/', function () use ($loader, $twig) {
    ControllerFactory::getController("suggestion", $loader, $twig)->call("sendSuggestion", [
        "object" => $_POST["object"],
        "suggestion" => $_POST['suggestion']
    ]);
    exit;
}, 'player');

$router->get('/cgu', function () use ($loader, $twig) {
    ControllerFactory::getController("policy", $loader, $twig)->call("showCgu");
    exit;
});

$router->get('/game/informations/:id', function ($id) use ($loader, $twig) {
    ControllerFactory::getController("game", $loader, $twig)->call("getGameInformations", ["id" => $id]);
    exit;
}, 'player');

$router->put('/suggest/deny/:id', function ($id) use ($loader, $twig) {
    ControllerFactory::getController("dashboard", $loader, $twig)->call("denySuggestion", ["id" => $id]);
    exit;
}, 'moderator');

$router->put('/suggest/accept/:id', function ($id) use ($loader, $twig) {
    ControllerFactory::getController("dashboard", $loader, $twig)->call("acceptSuggestion", ["id" => $id]);
    exit;
}, 'moderator');

$router->get('/', function () use ($loader, $twig) {
    ControllerFactory::getController("dashboard", $loader, $twig)->call("showDashboard");
    exit;
}, 'moderator');

$router->get('/suggest/:id', function ($id) use ($loader, $twig) {
    ControllerFactory::getController("dashboard", $loader, $twig)->call("getSuggestionInfo", ["id" => $id]);
    exit;
}, 'moderator');