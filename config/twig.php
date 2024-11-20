<?php

/**
 * @brief Fichier de configuration de Twig
 *
 * @file twig.php
 * @author Lucas ESPIET "lespiet@iutbayonne.univ-pau.fr"
 * @version 1.0
 * @date 2024-11-05
 */

use Twig\Extension\CoreExtension;
use Twig\Extension\DebugExtension;
use Twig\Extra\Intl\IntlExtension;

/**
 * @brief Instance de FilesystemLoader
 */
$loader = new Twig\Loader\FilesystemLoader(__DIR__.'/../src/templates');

/**
 * @brief Instance de Twig
 */
$twig = new Twig\Environment($loader, [
    'debug' => true,
]);

$twig->getExtension(CoreExtension::class)->setTimezone('Europe/Paris');
$twig->addExtension(new DebugExtension());
$twig->addExtension(new IntlExtension());

$twig->addGlobal('auth', [
    'pfpPath' => $_SESSION['pfpPath'] ?? null,
    'loggedIn' => isset($_SESSION['uuid']),
    'loggedUuid' => $_SESSION['uuid'] ?? null,
    'loggedUsername' => $_SESSION['username'] ?? null,
    'loggedComusCoin' => $_SESSION['comusCoin'] ?? null,
    'loggedElo' => $_SESSION['elo'] ?? null,
    'loggedXp' => $_SESSION['xp'] ?? null,
]);

$twig->addGlobal('error', [
    'code' => null,
    'message' => null,
]);