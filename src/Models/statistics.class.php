<?php
/**
 * @file    statistics.class.php
 * @author  Estéban DESESSARD
 * @brief   Fichier de déclaration et définition de la classe Statistics
 * @date    13/11/2024
 * @version 0.1
 */

namespace ComusParty\Models;

/**
 * @brief Classe Statistics
 * @details La classe Statistics recense l'ensemble des statistiques de profil d'un joueur
 */
class Statistics
{

    /**
     * @brief L'UUID du joueur
     * @var string|null
     */
    private ?string $playerUuid;

    /**
     * @brief Le nombre de parties jouées
     * @var int|null
     */
    private ?int $gamesPlayed;

    /**
     * @brief Le nombre de parties gagnées
     * @var int|null
     */
    private ?int $gamesWon;

    /**
     * @brief Le nombre de parties hébergées
     * @var int|null
     */
    private ?int $gamesHosted;

    /**
     * @brief Le constructeur de la classe Statistics
     * @param string|null $playerUuid L'UUID du joueur
     * @param int|null $gamesPlayed Le nombre de parties jouées
     * @param int|null $gamesWon Le nombre de parties gagnées
     * @param int|null $gamesHosted Le nombre de parties hébergées
     */
    public function __construct(
        ?string $playerUuid = null,
        ?int    $gamesPlayed = null,
        ?int    $gamesWon = null,
        ?int    $gamesHosted = null
    )
    {
        $this->playerUuid = $playerUuid;
        $this->gamesPlayed = $gamesPlayed;
        $this->gamesWon = $gamesWon;
        $this->gamesHosted = $gamesHosted;
    }

    /**
     * @brief Retourne l'UUID du joueur
     * @return string|null
     */
    public function getPlayerUuid(): ?string
    {
        return $this->playerUuid;
    }

    /**
     * @brief Modifie l'UUID du joueur
     * @param string|null $playerUuid
     */
    public function setPlayerUuid(?string $playerUuid): void
    {
        $this->playerUuid = $playerUuid;
    }

    /**
     * @brief Retourne le nombre de parties jouées
     * @return int|null
     */
    public function getGamesPlayed(): ?int
    {
        return $this->gamesPlayed;
    }

    /**
     * @brief Modifie le nombre de parties jouées
     * @param int|null $gamesPlayed
     */
    public function setGamesPlayed(?int $gamesPlayed): void
    {
        $this->gamesPlayed = $gamesPlayed;
    }

    /**
     * @brief Retourne le nombre de parties gagnées
     * @return int|null
     */
    public function getGamesWon(): ?int
    {
        return $this->gamesWon;
    }

    /**
     * @brief Modifie le nombre de parties gagnées
     * @param int|null $gamesWon
     */
    public function setGamesWon(?int $gamesWon): void
    {
        $this->gamesWon = $gamesWon;
    }

    /**
     * @brief Retourne le nombre de parties hébergées
     * @return int|null
     */
    public function getGamesHosted(): ?int
    {
        return $this->gamesHosted;
    }

    /**
     * @brief Modifie le nombre de parties hébergées
     * @param int|null $gamesHosted
     */
    public function setGamesHosted(?int $gamesHosted): void
    {
        $this->gamesHosted = $gamesHosted;
    }

    /**
     * @brief Retourne un tableau associatif contenant les statistiques
     * @return array
     */
    public function toArray(): array
    {
        return [
            "gamesPlayed" => $this->gamesPlayed,
            "gamesWon" => $this->gamesWon,
            "gamesHosted" => $this->gamesHosted
        ];
    }
}