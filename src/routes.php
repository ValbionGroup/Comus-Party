<?php

use models\Router;

$router = Router::getInstance();

$router->get('/', function () {
    echo "Page d'accueil<br/>";
    echo "A IMPLEMENTER";
    exit;
});

$router->get('/game/:code', function ($code) {
    echo "Page de jeu en cours : " . $code . "<br/>";
    echo "A IMPLEMENTER";
    exit;
});

$router->get('/shop', function () {
    echo "Page de la boutique<br/>";
    echo "A IMPLEMENTER";
    exit;
});

$router->get('/shop/basket', function () {
    echo "Page du panier<br/>";
    echo "A IMPLEMENTER";
    exit;
});

$router->post('/shop/basket/add/:id', function ($id) {
    echo "Ajout d'un article au panier : " . $id . "<br/>";
    echo "A IMPLEMENTER";
    exit;
});

$router->delete('/shop/basket/remove/:id', function ($id) {
    echo "Suppression d'un article du panier : " . $id . "<br/>";
    echo "A IMPLEMENTER";
    exit;
});

$router->get('/shop/basket/checkout', function () {
    echo "Page de paiement<br/>";
    echo "A IMPLEMENTER";
    exit;
});

$router->post('/shop/basket/checkout', function () {
    echo "Traitement du paiement<br/>";
    echo "A IMPLEMENTER";
    exit;
});

$router->get('/login', function () {
    echo "Page de connexion<br/>";
    echo "A IMPLEMENTER";
    exit;
});

$router->post('/login', function () {
    echo "Traitement de la connexion<br/>";
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

$router->get('/logout', function () {
    echo "Déconnexion<br/>";
    echo "A IMPLEMENTER";
    exit;
});

$router->get('/profile', function () {
    echo "Page de profil<br/>";
    echo "A IMPLEMENTER";
    exit;
});

$router->put('/profile', function () {
    echo "Mise à jour du profil<br/>";
    echo "A IMPLEMENTER";
    exit;
});