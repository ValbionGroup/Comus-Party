<?php
require_once  '../../include.php';

//var_dump($_ENV);
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

//
    $controller = ControllerFactory::getController($controller, $loader, $twig);
    $controller->call($method);

}catch (Exception $e){
    die ($e->getMessage());
}



