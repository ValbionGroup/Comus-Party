<?php
/**
 * @file    Dashboard.php
 * @brief   Fichier de déclaration et définition de la classe Dashboard (Sockets)
 * @author  Estéban DESESSARD
 * @date    25/02/2024
 * @version 1.0
 */

namespace ComusParty\App\Sockets;

use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;


/**
 * @brief Classe Dashboard (Sockets)
 */
class Dashboard implements MessageComponentInterface
{
    protected SplObjectStorage $clients;

    public function __construct()
    {
        $this->clients = new SplObjectStorage;
    }

    function onOpen(ConnectionInterface $conn): void
    {
        $conn->send(json_encode(['message' => 'Connection established']));
        $this->clients->attach($conn);
    }

    function onClose(ConnectionInterface $conn): void
    {
        $this->clients->detach($conn);
    }

    function onError(ConnectionInterface $conn, Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    function onMessage(ConnectionInterface $from, $msg): void
    {
        $command = json_decode($msg, true)['command'];
        foreach ($this->clients as $client) {
            $client->send(json_encode(['message' => $command]));
        }
    }
}