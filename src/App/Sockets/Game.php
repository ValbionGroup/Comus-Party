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

/**
 * @brief Classe Game (Sockets)
 * @details Classe permettant de gérer le jeu en temps réel
 */
class Game implements MessageComponentInterface
{
    /**
     * @brief Liste des clients connectés
     * @var SplObjectStorage
     */
    protected SplObjectStorage $clients;

    /**
     * @brief Liste des parties en cours
     * @var array
     */
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
     * @brief Fonction appelée lors de la connexion d'un joueur
     * @details Envoie un message de confirmation de connexion
     * @param ConnectionInterface $conn La connexion du joueur
     */
    function onOpen(ConnectionInterface $conn): void
    {
        $conn->send(json_encode(['message' => 'Connection established']));
    }

    /**
     * @brief Fonction appelée lors de la réception d'un message
     * @details Gère l'affichage des joueurs en temps réel
     * @param ConnectionInterface $from La connexion du joueur
     * @param string $msg Le message reçu
     * @throws Exception
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
     * @brief Fonction appelée lors de l'arrivée et la déconnexion d'un joueur
     * @details Met à jour la liste des joueurs
     * @param string $game
     * @throws Exception
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
            "pfp" => $player['player']->getActivePfp()
        ], $players);

        $this->sendToGame($game, "updatePlayer", json_encode($jsonPlayer));
    }

    /**
     * @brief Fonction permettant d'envoyer un message à une partie
     * @param string $game La partie à laquelle envoyer le message
     * @param string $command La commande à envoyer
     * @param string $content Le contenu du message
     */
    private function sendToGame(string $game, string $command, string $content): void
    {
        foreach ($this->games[$game] as $client) {
            $client->send(json_encode(['command' => $command, 'content' => $content]));
        }
    }

    /**
     * @brief Fonction appelée lorsque la partie est démarrée
     * @details Redirige les joueurs vers la partie
     * @param string $game
     * @param string $uuid
     * @throws Exception
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
     * @brief Fonction appelée lors de la déconnexion d'un joueur
     * @details Retire le joueur de la liste des clients
     * @param ConnectionInterface $conn La connexion du joueur
     * @throws Exception
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
     * @brief Fonction appelée lors d'une erreur
     * @param ConnectionInterface $conn La connexion du joueur
     * @param Exception $e L'exception
     */
    public function onError(ConnectionInterface $conn, Exception $e): void
    {
        echo "Erreur: {$e->getMessage()}\n";
        $conn->close();
    }

    /**
     * @brief Fonction permettant d'échapper les caractères spéciaux
     * @param string $string La chaîne à échapper
     * @return string La chaîne échappée
     */
    protected function escape(string $string): string
    {
        return htmlspecialchars($string);
    }
}