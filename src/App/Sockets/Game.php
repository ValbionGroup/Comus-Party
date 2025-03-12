<?php
/**
 * @brief Fichier de déclaration et définition de la classe Game (Sockets)
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

    /**
     * @brief Constructeur de la classe Game
     */
    public function __construct()
    {
        $this->clients = new SplObjectStorage;
        $this->games = [];
    }

    /**
     * @brief Ouvre une connexion
     * @param ConnectionInterface $conn Connexion à ouvrir
     * @return void
     */
    function onOpen(ConnectionInterface $conn): void
    {
        $conn->send(json_encode(['message' => 'Connection established']));
    }

    /**
     * @brief Reçoit un message
     * @param ConnectionInterface $from Connexion d'origine
     * @param string $msg Message reçu
     * @return void
     * @throws Exception Exception levée dans le cas d'une erreur
     */
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

        if (!isset($this->games[$game])) {
            $this->games[$game] = [];
        }

        if (!in_array($from, $this->games[$game])) {
            $this->games[$game][] = $from;
        }

        switch ($command) {
            case 'quitGame':
            case 'joinGame':
                $this->updatePlayer($game);
                break;
            case 'startGame':
                $this->redirectUserToGame($game, $uuid);
                break;
            default:
                break;
        }
    }

    /**
     * @brief Envoie un signal à tous les clients avec la nouvelle liste des joueurs
     * @param string $game Code de la partie
     * @return void
     * @throws Exception Exception levée quand la partie n'existe pas
     */
    private function updatePlayer(string $game): void
    {
        $gameRecord = (new GameRecordDAO(Db::getInstance()->getConnection()))->findByCode($game);

        if (!isset($gameRecord)) {
            return;
        }

        $players = $gameRecord->getPlayers();
        $jsonPlayer = array_map(fn($player) => [
            "uuid" => $player['player']->getUuid(),
            "username" => $player['player']->getUsername(),
            "pfp" => $player['player']->getActivePfp(),
            "isHost" => $player['player']->getUuid() == $gameRecord->getHostedBy()->getUuid(),
        ], $players);

        $this->sendToGame($game, "updatePlayers", json_encode($jsonPlayer));
    }

    /**
     * @brief Envoie un message à tous les clients d'une partie
     * @param string $game Code de la partie
     * @param string $command Commande à envoyer
     * @param string $content Contenu à envoyer
     * @return void
     */
    private function sendToGame(string $game, string $command, string $content): void
    {
        foreach ($this->games[$game] as $client) {
            $client->send(json_encode(['command' => $command, 'content' => $content]));
        }
    }

    /**
     * @brief Redirige un joueur vers la partie si elle a commencé
     * @param string $game Code de la partie
     * @param string $uuid UUID du joueur
     * @return void
     * @throws Exception Exception levée quand la partie n'existe pas
     */
    private function redirectUserToGame(string $game, string $uuid): void
    {
        $gameRecord = (new GameRecordDAO(Db::getInstance()->getConnection()))->findByCode($game);

        if ($gameRecord->getState() == GameRecordState::STARTED &&
            $gameRecord->getHostedBy()->getUuid() == $uuid) {
            $this->sendToGame($game, "gameStarted", json_encode(["message" => "Game started!"]));
        }
    }

    /**
     * @brief Ferme la connexion d'un client
     * @param ConnectionInterface $conn Connexion à fermer
     * @return void
     * @throws Exception Exception levée dans le cas d'une erreur
     */
    public function onClose(ConnectionInterface $conn): void
    {
        foreach ($this->games as $game => $clients) {
            $key = array_search($conn, $clients);
            if ($key !== false) {
                $this->updatePlayer($game);
                unset($this->games[$game][$key]);
            }
        }

        $this->clients->detach($conn);
    }

    /**
     * @brief Gère les erreurs
     * @param ConnectionInterface $conn Connexion à fermer
     * @param Exception $e Exception à gérer
     * @return void
     */
    public function onError(ConnectionInterface $conn, Exception $e): void
    {
        echo "Erreur: {$e->getMessage()}\n";
        $conn->close();
    }

    /**
     * @param string $string Chaîne à échapper
     * @return string Chaîne échappée
     */
    protected function escape(string $string): string
    {
        return htmlspecialchars($string);
    }
}