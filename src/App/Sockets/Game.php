<?php
/**
 * @brief Fichier de définition de la classe Game (Sockets)
 *
 * @file Game.php
 * @author Lucas ESPIET "espiet.l@valbion.com"
 * @version 1.0
 * @date 2024-12-24
 */

namespace ComusParty\App\Sockets;

use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class Game implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    /**
     * @inheritDoc
     */
    function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "new connection: ({$conn->resourceId})\n";
    }

    /**
     * @inheritDoc
     */
    function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "connection {$conn->resourceId} has disconnected\n";
    }

    /**
     * @inheritDoc
     */
    function onError(ConnectionInterface $conn, Exception $e)
    {
        echo "error occured: {$e->getMessage()}\n";
        $conn->close();
    }

    /**
     * @inheritDoc
     */
    function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);
        // handle incomming messages and brodcast to all clients
        foreach ($this->clients as $client) {
            if($from !== $client) {
                $client->send(json_encode($data));
            }
        }
    }
}