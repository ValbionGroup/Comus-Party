<?php

use Twig\Extension\CoreExtension;
use Twig\Extension\DebugExtension;
use Twig\Extra\Intl\IntlExtension;

$loader = new Twig\Loader\FilesystemLoader(__DIR__.'/../src/templates');

$twig = new Twig\Environment($loader, [
    'debug' => true,
]);

$twig->getExtension(CoreExtension::class)->setTimezone('Europe/Paris');
$twig->addExtension(new DebugExtension());
$twig->addExtension(new IntlExtension());

//$twig->addGlobal('playerConnected', $_SESSION['playerConnected'] ?? null);
//$twig->addGlobal('pfpPath', $_SESSION['pfpPath'] ?? null);

$twig->addGlobal('auth', [
    'pfpPath' => $_SESSION['pfpPath'] ?? null,
    'loggedIn' => isset($_SESSION['uuid'])
]);