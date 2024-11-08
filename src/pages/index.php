<?php

global $loader, $twig;
require_once  __DIR__.'/../../include.php';


try {
    $controller = $_GET["controller"] ?? '';
    $method = $_GET["method"] ?? '';

    if($controller == '' && $method == ''){
        $controller = 'game';
        $method = 'show';
    }

    if($controller == ''){
        throw new Exception("Le controleur n'est pas défini");
    }

    if($method == ''){
        throw new Exception("La méthode n'est pas définie");
    }

    $controller = ControllerFactory::getController($controller, $loader, $twig);
    $controller->call($method);

}catch (Exception $e){
    displayError($e);
}