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
use ComusParty\App\Sockets\Game;
use Ratchet\WebSocket\WsServer;

$server = new Ratchet\App('localhost', 8315);
$server->route('/chat/{token}', new WsServer(new Chat()));
$server->route('/game/{token}', new WsServer(new Game()));
$server->run();