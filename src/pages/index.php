<?php
require_once  '../../include.php';
try {
    $controller = $_GET["controller"] ?? '';
    $methode = $_GET["method"] ?? '';

    if($controller == '' && $methode == ''){
        $controller = 'accueil';
        $methode = 'afficher';
    }

    if($controller == ''){
        throw new Exception("Le controleur n'est pas défini");
    }

    if($methode == ''){
        throw new Exception("La méthode n'est pas défini");
    }
}catch (Exception $e){
    die ($e->getMessage());
}



$template = $twig->load('accueil.twig');

echo $template->render();