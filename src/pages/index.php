<?php
require_once  '../../include.php';
try {
    $controller = $_GET["controller"] ?? '';
    $method = $_GET["method"] ?? '';

    if($controller == '' && $method == ''){
        $controller = 'accueil';
        $method = 'afficher';
    }

    if($controller == ''){
        throw new Exception("Le controleur n'est pas défini");
    }

    if($method == ''){
        throw new Exception("La méthode n'est pas définie");
    }
}catch (Exception $e){
    die ($e->getMessage());
}



$template = $twig->load('shop.twig');

echo $template->render();