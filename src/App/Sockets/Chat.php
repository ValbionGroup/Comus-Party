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
use DateMalformedStringException;
use DateTime;
use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;

/**
 * @brief Classe Chat (Sockets)
 * @details Classe permettant de gérer le chat en temps réel
 */
class Chat implements MessageComponentInterface
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
     * @brief Constructeur de la classe Chat
     */
    public function __construct()
    {
        $this->clients = new SplObjectStorage;
        $this->games = [];
    }

    /**
     * @brief Fonction appelée lors de la connexion d'un joueur
     * @details Ajoute le joueur à la liste des clients
     * @param ConnectionInterface $conn La connexion du joueur
     */
    public function onOpen(ConnectionInterface $conn): void
    {
        // Récupérer le lien auquel il est connecté
        $gameCode = explode("=", $conn->httpRequest->getUri()->getQuery())[1];

        if (!isset($this->games[$gameCode])) {
            $this->games[$gameCode] = [];
        }
        $this->games[$gameCode][] = $conn;

        $this->clients->attach($conn);
    }

    /**
     * @brief Fonction appelée lors de la réception d'un message
     * @details Envoie le message à tous les joueurs de la partie
     * @param ConnectionInterface $from La connexion du joueur
     * @param string $msg Le message reçu
     * @throws DateMalformedStringException Exception lancée si la date est mal formée
     */
    public function onMessage(ConnectionInterface $from, $msg): void
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
     * @brief Fonction permettant d'échapper les caractères spéciaux
     * @param string $string La chaîne à échapper
     * @return string La chaîne échappée
     */
    protected function escape(string $string): string
    {
        return htmlspecialchars($string);
    }

    /**
     * @brief Fonction appelée lors de la déconnexion d'un joueur
     * @details Retire le joueur de la liste des clients
     * @param ConnectionInterface $conn La connexion du joueur
     */
    public function onClose(ConnectionInterface $conn): void
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

    /**
     * @brief Fonction appelée lorsqu'une erreur survient
     * @details Ferme la connexion
     * @param ConnectionInterface $conn La connexion du joueur
     * @param Exception $e L'exception
     */
    public function onError(ConnectionInterface $conn, Exception $e): void
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
