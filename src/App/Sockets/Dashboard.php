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
 * @details Classe permettant de gérer le dashboard en temps réel
 */
class Dashboard implements MessageComponentInterface
{
    /**
     * @brief Liste des clients connectés
     * @var SplObjectStorage
     */
    protected SplObjectStorage $clients;

    /**
     * @brief Constructeur de la classe Dashboard
     */
    public function __construct()
    {
        $this->clients = new SplObjectStorage;
    }

    /**
     * @brief Fonction appelée lors de la connexion d'un modérateur
     * @details Ajoute le modérateur à la liste des clients
     * @param ConnectionInterface $conn La connexion du joueur
     */
    function onOpen(ConnectionInterface $conn): void
    {
        $conn->send(json_encode(['message' => 'Connection established']));
        $this->clients->attach($conn);
    }

    /**
     * @brief Fonction appelée lors de la déconnexion d'un modérateur
     * @details Supprime le modérateur de la liste des clients
     * @param ConnectionInterface $conn La connexion du joueur
     */
    function onClose(ConnectionInterface $conn): void
    {
        $this->clients->detach($conn);
    }

    function onError(ConnectionInterface $conn, Exception $e): void
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    /**
     * @brief Fonction appelée lors de la réception d'un message
     * @details Envoie le message reçu à tous les clients connectés
     * @param ConnectionInterface $from La connexion du joueur
     * @param string $msg Le message reçu
     */
    function onMessage(ConnectionInterface $from, $msg): void
    {
        $command = json_decode($msg, true)['command'];
        foreach ($this->clients as $client) {
            $client->send(json_encode(['message' => $command]));
        }
    }
}