<?php
/**
 * @brief Fichier de déclaration et définition de la classe GameRecord
 *
 * @file gamerecord.class.php
 * @author Lucas ESPIET "espiet.l@valbion.com"
 * @version 1.0
 * @date 2024-12-18
 */

namespace ComusParty\Models;


use DateTime;
use Random\RandomException;

/**
 * @brief Enumération des états d'une partie
 *
 * @details Cette énumération définit les trois états possibles d'une partie :
 *  - En attente (WAITING)
 *  - Démarré (STARTED)
 *  - Terminé (FINISHED)
 *
 * @enum GameRecordState
 */
enum GameRecordState
{
    /**
     * @brief L'état WAITING indique que la partie est en attente de joueurs pour démarrer.
     */
    case WAITING;
    /**
     * @brief L'état STARTED indique que la partie est en cours.
     */
    case STARTED;
    /**
     * @brief L'état FINISHED indique que la partie est terminée.
     */
    case FINISHED;
    /**
     * @brief L'état UNKNOWN indique que l'état de la partie est inconnu.
     * @details Cet état est utilisé par défaut lors de la création d'une partie.
     */
    case UNKNOWN;
}

/**
 * @brief Classe GameRecord
 * @details La classe GameRecord permet de représenter une partie avec ses attributs et ses méthodes
 */
class GameRecord
{
    /**
     * @brief Identifiant de la partie
     * @var string Identifiant de la partie
     */
    private string $code;
    /**
     * @brief Token de la partie
     * @details Le token est utilisé pour authentifier une partie auprès de Comus notamment pour les requêtes de mise à jour de la partie.
     * @var string|null Token de la partie
     */
    private ?string $token;
    /**
     * @brief Jeu de la partie
     * @var Game Jeu de la partie
     */
    private Game $game;
    /**
     * @brief Joueur qui a créé la partie
     * @var Player Joueur qui a créé la partie
     */
    private Player $hostedBy;
    /**
     * @brief Joueurs de la partie
     * @var Player[]|null Joueurs de la partie
     */
    private ?array $players;
    /**
     * @brief Etat de la partie
     * @var GameRecordState Etat de la partie
     */
    private GameRecordState $state;
    /**
     * @brief Indique si la partie est privée
     * @var bool Indique si la partie est privée
     */
    private bool $private;
    /**
     * @brief Date de création de la partie
     * @var DateTime Date de création de la partie
     */
    private DateTime $createdAt;
    /**
     * @brief Date de dernière mise à jour de la partie
     * @var DateTime Date de dernière mise à jour de la partie
     */
    private DateTime $updatedAt;
    /**
     * @brief Date de fin de la partie
     * @var DateTime|null Date de fin de la partie
     */
    private ?DateTime $finishedAt;

    /**
     * @brief Constructeur de la classe GameRecord
     *
     * @param string $code Identifiant de la partie
     * @param string|null $token Token de la partie
     * @param Game $game Jeu de la partie
     * @param Player $hostedBy Joueur qui a créé la partie
     * @param Player[]|null $players Joueurs de la partie
     * @param GameRecordState $state Etat de la partie
     * @param bool $isPrivate Indique si la partie est privée
     * @param DateTime|null $createdAt Date de création de la partie
     * @param DateTime|null $updatedAt Date de dernière mise à jour de la partie
     * @param DateTime|null $finishedAt Date de fin de la partie
     */
    public function __construct(string $code, Game $game, Player $hostedBy, ?array $players, GameRecordState $state, bool $isPrivate, ?string $token = null, ?DateTime $createdAt = null, ?DateTime $updatedAt = null, ?DateTime $finishedAt = null)
    {
        $this->code = $code;
        $this->game = $game;
        $this->hostedBy = $hostedBy;
        $this->players = $players;
        $this->state = $state;
        $this->private = $isPrivate;
        $this->token = $token;
        $this->createdAt = $createdAt ?? new DateTime();
        $this->updatedAt = $updatedAt ?? new DateTime();
        $this->finishedAt = $finishedAt;
    }

    /**
     * @brief Getter de l'attribut uuid
     *
     * @return string Identifiant de la partie
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @brief Setter de l'attribut uuid
     *
     * @param string $code Identifiant de la partie
     * @return void
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @brief Getter de l'attribut token
     *
     * @return string|null Token de la partie
     */
    public function getToken(): string|null
    {
        return $this->token;
    }

    /**
     * @brief Setter de l'attribut token
     *
     * @param string|null $token Token de la partie
     * @return void
     */
    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    /**
     * @brief Génère un token aléatoire pour la partie
     * @return string Token généré en clair
     * @throws RandomException Exception levée en cas d'erreur lors de la génération du token
     */
    public function generateToken(): string
    {
        $token = bin2hex(random_bytes(16));
        $this->setToken(hash('sha256', $token));
        return $token;
    }

    /**
     * @brief Getter de l'attribut game
     *
     * @return Game Jeu de la partie
     */
    public function getGame(): Game
    {
        return $this->game;
    }

    /**
     * @brief Setter de l'attribut game
     *
     * @param Game $game Jeu de la partie
     * @return void
     */
    public function setGame(Game $game): void
    {
        $this->game = $game;
    }

    /**
     * @brief Getter de l'attribut hostedBy
     *
     * @return Player Joueur qui a créé la partie
     */
    public function getHostedBy(): Player
    {
        return $this->hostedBy;
    }

    /**
     * @brief Setter de l'attribut hostedBy
     *
     * @param Player $player Joueur qui a créé la partie
     * @return void
     */
    public function setHostedBy(Player $player): void
    {
        $this->hostedBy = $player;
    }

    /**
     * @brief Getter de l'attribut players
     *
     * @return Player[]|null Tableau des joueurs
     */
    public function getPlayers(): ?array
    {
        return $this->players;
    }

    /**
     * @brief Setter de l'attribut players
     *
     * @param Player[]|null $players Tableau des joueurs
     * @return void
     */
    public function setPlayers(?array $players): void
    {
        $this->players = $players;
    }

    /**
     * @brief Getter de l'attribut state
     *
     * @return GameRecordState Etat de la partie
     */
    public function getState(): GameRecordState
    {
        return $this->state;
    }

    /**
     * @brief Setter de l'attribut state
     *
     * @param GameRecordState $state Etat de la partie
     * @return void
     */
    public function setState(GameRecordState $state): void
    {
        $this->state = $state;
    }

    /**
     * @brief Getter de l'attribut isPrivate
     *
     * @return bool Indique si la partie est privée
     */
    public function isPrivate(): bool
    {
        return $this->private;
    }

    /**
     * @brief Setter de l'attribut isPrivate
     *
     * @param bool $isPrivate Indique si la partie est privée
     * @return void
     */
    public function setPrivate(bool $isPrivate): void
    {
        $this->private = $isPrivate;
    }

    /**
     * @brief Getter de l'attribut createdAt
     *
     * @return DateTime Date de création de la partie
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @brief Setter de l'attribut createdAt
     *
     * @param DateTime $createdAt Date de création de la partie
     * @return void
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @brief Getter de l'attribut updatedAt
     *
     * @return DateTime Date de dernière mise à jour de la partie
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @brief Setter de l'attribut updatedAt
     *
     * @param DateTime $updatedAt Date de dernière mise à jour de la partie
     * @return void
     */
    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @brief Getter de l'attribut finishedAt
     *
     * @return DateTime|null Date de fin de la partie
     */
    public function getFinishedAt(): ?DateTime
    {
        return $this->finishedAt;
    }

    /**
     * @brief Setter de l'attribut finishedAt
     *
     * @param DateTime|null $finishedAt Date de fin de la partie
     * @return void
     */
    public function setFinishedAt(?DateTime $finishedAt): void
    {
        $this->finishedAt = $finishedAt;
    }

    /**
     * @brief Ajoute un joueur à la partie
     * @param Player $player Joueur à ajouter
     * @return void
     */
    public function addPlayer(Player $player): void
    {
        $this->players[] = $player;
    }

    /**
     * @brief Supprime un joueur de la partie
     * @param Player $player Joueur à supprimer
     * @return void
     */
    public function removePlayer(Player $player): void
    {
        $key = array_search($player, $this->players);
        if ($key !== false) {
            unset($this->players[$key]);
        }
    }
}