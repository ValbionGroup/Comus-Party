<?php
/**
 * @brief Fichier de déclaration et définition de la classe Chat (Sockets)
 *
 * @file Chat.php
 * @author Lucas ESPIET "espiet.l@valbion.com"
 * @version 1.0
 * @date 2024-12-24
 */

namespace ComusParty\App\Sockets;

use ComusParty\App\Db;
use ComusParty\Models\PenaltyDAO;
use ComusParty\Models\PlayerDAO;
use DateTime;
use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;

/**
 * @brief Classe Chat (Sockets)
 */
class Chat implements MessageComponentInterface
{
    protected SplObjectStorage $clients;
    protected array $games;

    public function __construct()
    {
        $this->clients = new SplObjectStorage;
        $this->games = [];
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Récupérer le lien auquel il est connecté
        $gameCode = explode("=", $conn->httpRequest->getUri()->getQuery())[1];

        if (!isset($this->games[$gameCode])) {
            $this->games[$gameCode] = [];
        }
        $this->games[$gameCode][] = $conn;

        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);

        if (!isset($data["content"]) || !isset($data["author"]) || !isset($data["game"])) {
            return;
        }

        $content = $this->escape($data["content"]);
        $author = $this->escape($data["author"]);
        $game = $data["game"];

        $playerManager = new PlayerDAO(Db::getInstance()->getConnection());
        $player = $playerManager->findByUsername($author);

        $penaltyManager = new PenaltyDAO(Db::getInstance()->getConnection());
        $penalty = $penaltyManager->findLastMutedByPlayerUuid($player->getUuid());


        if (isset($penalty)) {
            $endDate = $penalty->getCreatedAt()->modify("+" . $penalty->getDuration() . "hour");
            if ($endDate > new DateTime()) {
                return;
            }
        }

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

    /**
     * @param string $string The string to escape
     * @return string The escaped string
     */
    protected function escape(string $string): string
    {
        return htmlspecialchars($string);
    }

    public function onClose(ConnectionInterface $conn)
    {
        // Retirer le joueur
        foreach ($this->games as $gameId => &$players) {
            $players = array_filter($players, function ($player) use ($conn) {
                return $player !== $conn;
            });

            // Supprimer la game si vide
            if (empty($players)) {
                unset($this->games[$gameId]);
            }
        }

        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
