<?php
/**
 * @file    Dashboard.php
 * @brief   Le fichier contient la déclaration et la définition de la classe Dashboard (Sockets)
 * @author  Estéban DESESSARD
 * @date    25/02/2024
 * @version 1.0
 */

namespace ComusParty\App\Sockets;

use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;

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
        // TODO: Implement onMessage() method.
    }
}