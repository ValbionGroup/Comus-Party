<?php
/**
 * @brief Fichier permettant de lancer le serveur WebSocket
 *
 * @file ws-server.php
 * @author Lucas ESPIET "espiet.l@valbion.com"
 * @version 1.0
 * @date 2024-12-24
 */

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/App/Sockets/Chat.php';
require __DIR__ . '/src/App/Sockets/Game.php';

require_once __DIR__ . '/include.php';

use ComusParty\App\Sockets\Chat;
use ComusParty\App\Sockets\Dashboard;
use ComusParty\App\Sockets\Game;
use Ratchet\WebSocket\WsServer;

$allowedOrigins = [
    'game.comus-party.com',
    'comus-party.com',
    'www.comus-party.com',
    'localhost',
];

$server = new Ratchet\App('sockets.comus-party.com', 21000);
$server->route('/chat/{token}', new WsServer(new Chat()), $allowedOrigins);
$server->route('/game/{token}', new WsServer(new Game()), $allowedOrigins);
$server->route('/dashboard', new WsServer(new Dashboard()), $allowedOrigins);
$server->run();