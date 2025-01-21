<?php
/**
 * @brief Fichier de définition de la classe Chat (Sockets)
 *
 * @file Chat.php
 * @author Lucas ESPIET "espiet.l@valbion.com"
 * @version 1.0
 * @date 2024-12-24
 */

namespace ComusParty\App\Sockets;

use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;

class Chat implements MessageComponentInterface
{
    protected SplObjectStorage $clients;
    protected array $games = [];

    public function __construct()
    {
        $this->clients = new SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);

        if (!isset($data["content"]) || !isset($data["author"]) || !isset($data["game"])) {
            return;
        };

        $content = $this->escape($data["content"]);
        $author = $this->escape($data["author"]);
        $game = $data["game"];

        if (!isset($this->games[$game])) {
            $this->games[$game] = [];
        }

        if (!in_array($from, $this->games[$game])) {
            $this->games[$game][] = $from;
        }

        foreach ($this->games[$game] as $player) {
            $player->send(json_encode([
                "author" => $author,
                "content" => $content,
            ]));
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    protected function escape(string $string): string
    {
        return htmlspecialchars($string);
    }
}