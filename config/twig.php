<?php
/**
 * @brief Fichier de configuration de Twig
 *
 * @file twig.php
 * @author Lucas ESPIET "lespiet@iutbayonne.univ-pau.fr"
 * @version 1.0
 * @date 2024-11-05
 */

namespace ComusParty;

use Twig\Environment;
use Twig\Extension\CoreExtension;
use Twig\Extension\DebugExtension;
use Twig\Extra\Date\DateExtension;
use Twig\Extra\Intl\IntlExtension;
use Twig\Loader\FilesystemLoader;

/**
 * @brief Instance de FilesystemLoader
 */
$loader = new FilesystemLoader(__DIR__ . '/../src/templates');

/**
 * @brief Instance de Twig
 */
$twig = new Environment($loader, [
    'debug' => true,
]);

$twig->getExtension(CoreExtension::class)->setTimezone('Europe/Paris');
$twig->addExtension(new DebugExtension());
$twig->addExtension(new IntlExtension());
$twig->addExtension(new DateExtension());

$twig->addGlobal('auth', [
    'pfpPath' => $_SESSION['pfpPath'] ?? null,
    'loggedIn' => isset($_SESSION['uuid']),
    'loggedUuid' => $_SESSION['uuid'] ?? null,
    'loggedUsername' => $_SESSION['username'] ?? null,
    'loggedComusCoin' => $_SESSION['comusCoin'] ?? null,
    'loggedElo' => $_SESSION['elo'] ?? null,
    'loggedXp' => $_SESSION['xp'] ?? null,
    'role' => $_SESSION['role'] ?? null,
    'firstName' => $_SESSION['firstName'] ?? null,
    'lastName' => $_SESSION['lastName'] ?? null,
]);

if (isset($_SESSION['error'])) {
    $twig->addGlobal('error', $_SESSION['error']);
    unset($_SESSION['error']);
}

if (isset($_SESSION['success'])) {
    $twig->addGlobal('success', $_SESSION['success']);
    unset($_SESSION['success']);
}