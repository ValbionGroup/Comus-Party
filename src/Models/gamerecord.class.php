<?php
/**
 * @brief Fichier contenant la déclaration et la définition de la classe GameRecord
 *
 * @file gamerecord.class.php
 * @author Lucas ESPIET "espiet.l@valbion.com"
 * @version 1.0
 * @date 2024-12-18
 */

namespace ComusParty\Models;


use DateTime;

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
    private string $uuid;
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
     * @var DateTime|null ate de fin de la partie
     */
    private ?DateTime $finishedAt;

    /**
     * @brief Constructeur de la classe GameRecord
     *
     * @param string $uuid Identifiant de la partie
     * @param Game $game Jeu de la partie
     * @param Player $hostedBy Joueur qui a créé la partie
     * @param array|null $players Joueurs de la partie
     * @param GameRecordState $state Etat de la partie
     * @param DateTime $createdAt Date de création de la partie
     * @param DateTime $updatedAt Date de dernière mise à jour de la partie
     * @param DateTime|null $finishedAt Date de fin de la partie
     */
    public function __construct(string $uuid, Game $game, Player $hostedBy, ?array $players, GameRecordState $state, DateTime $createdAt, DateTime $updatedAt, ?DateTime $finishedAt)
    {
        $this->uuid = $uuid;
        $this->game = $game;
        $this->hostedBy = $hostedBy;
        $this->players = $players;
        $this->state = $state;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->finishedAt = $finishedAt;
    }

    /**
     * @brief Getter de l'attribut uuid
     *
     * @return string Identifiant de la partie
     */
    public function getUuid(): string
    {
        return $this->uuid;
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
     * @brief Getter de l'attribut hostedBy
     *
     * @return Player Joueur qui a créé la partie
     */
    public function getHostedBy(): Player
    {
        return $this->hostedBy;
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
     * @brief Getter de l'attribut createdAt
     *
     * @return DateTime Date de création de la partie
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
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
}