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

use ComusParty\App\Db;
use ComusParty\Models\GameRecordDAO;
use ComusParty\Models\GameRecordState;
use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;

class Game implements MessageComponentInterface
{
    protected SplObjectStorage $clients;
    protected array $games;

    public function __construct()
    {
        $this->clients = new SplObjectStorage;
        $this->games = [];
    }

    function onOpen(ConnectionInterface $conn): void
    {
        $conn->send(json_encode(['message' => 'Connection established']));
    }

    function onMessage(ConnectionInterface $from, $msg): void
    {
        $data = json_decode($msg, true);

        if (!isset($data['uuid']) || !isset($data['command']) || !isset($data['game'])) {
            $from->send(json_encode(['error' => 'Message invalide']));
            return;
        }

        $uuid = $data['uuid'];
        $game = $data['game'];
        $command = $data['command'];
        $sessionData = $data['sessionData'];

        if (!isset($this->games[$game])) {
            $this->games[$game] = [];
        }

        if (!in_array($from, $this->games[$game])) {
            $this->games[$game][] = $from;
        }

        switch ($command) {
            case 'quitGame':
            case 'joinGame':
                $this->updatePlayer($game, $sessionData);
                break;
            case 'startGame':
                $this->redirectUserToGame($game, $uuid);
                break;
            default:
                break;
        }
    }

    private function updatePlayer(string $game, array | null $sessionData): void
    {
        $gameRecord = (new GameRecordDAO(Db::getInstance()->getConnection()))->findByCode($game);

        $players = $gameRecord->getPlayers();
        $jsonPlayer = array_map(fn($player) => [
            "uuid" => $player['player']->getUuid(),
            "username" => $player['player']->getUsername(),
            "pfp" => $player['player']->getActivePfp()
        ], $players);

        if (isset($sessionData['is_guest']) && $sessionData['is_guest'] === 'true') {
            $guestPlayer = [
                "uuid" => $sessionData['uuid'],
                "username" => $sessionData['username'],
                "elo" => $sessionData['elo'],
                "xp" => $sessionData['xp'],
                "pfp" => $sessionData['pfpPath']
            ];
            $jsonPlayer[] = $guestPlayer;
        }

        $this->sendToGame($game, "updatePlayer", json_encode($jsonPlayer));
    }

    private function sendToGame(string $game, string $command, string $content): void
    {
        foreach ($this->games[$game] as $client) {
            $client->send(json_encode(['command' => $command, 'content' => $content]));
        }
    }

    private function redirectUserToGame(string $game, string $uuid): void
    {
        $gameRecord = (new GameRecordDAO(Db::getInstance()->getConnection()))->findByCode($game);

        if ($gameRecord->getState() == GameRecordState::STARTED &&
            $gameRecord->getHostedBy()->getUuid() == $uuid) {
            $this->sendToGame($game, "gameStarted", json_encode(["message" => "Game started!"]));
        }
    }

    public function onClose(ConnectionInterface $conn): void
    {
        foreach ($this->games as $game => $clients) {
            $key = array_search($conn, $clients);
            if ($key !== false) {
                $this->updatePlayer($game, null);
                unset($this->games[$game][$key]);
            }
        }

        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, Exception $e): void
    {
        echo "Erreur: {$e->getMessage()}\n";
        $conn->close();
    }

    /**
     * @param string $string The string to escape
     * @return string The escaped string
     */
    protected function escape(string $string): string
    {
        return htmlspecialchars($string);
    }
}