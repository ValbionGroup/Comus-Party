<?php

/**
 * La classe Statistics recense l'ensemble des statistiques de profil d'un joueur
 */
class Statistics {

    /**
     * L'UUID du joueur
     *
     * @var string|null
     */
    private ?string $playerUuid;

    /**
     * Le nombre de parties jouées
     *
     * @var int|null
     */
    private ?int $gamesPlayed;

    /**
     * Le nombre de parties gagnées
     *
     * @var int|null
     */
    private ?int $gamesWon;

    /**
     * Le nombre de parties hébergées
     *
     * @var int|null
     */
    private ?int $gamesHosted;

    /**
     * Constructeur de la classe Statistics
     *
     * @param string|null $playerUuid
     * @param int|null $gamesPlayed
     * @param int|null $gamesWon
     * @param int|null $gamesHosted
     */
    public function __construct(
        ?string $playerUuid = null,
        ?int $gamesPlayed = null,
        ?int $gamesWon = null,
        ?int $gamesHosted = null
    ) {
        $this->playerUuid = $playerUuid;
        $this->gamesPlayed = $gamesPlayed;
        $this->gamesWon = $gamesWon;
        $this->gamesHosted = $gamesHosted;
    }

    /**
     * Retourne l'UUID du joueur
     *
     * @return string|null
     */
    public function getPlayerUuid(): ?string
    {
        return $this->playerUuid;
    }

    /**
     * Modifie l'UUID du joueur
     *
     * @param string|null $playerUuid
     */
    public function setPlayerUuid(?string $playerUuid): void
    {
        $this->playerUuid = $playerUuid;
    }

    /**
     * Retourne le nombre de parties jouées
     *
     * @return int|null
     */
    public function getGamesPlayed(): ?int
    {
        return $this->gamesPlayed;
    }

    /**
     * Modifie le nombre de parties jouées
     *
     * @param int|null $gamesPlayed
     */
    public function setGamesPlayed(?int $gamesPlayed): void
    {
        $this->gamesPlayed = $gamesPlayed;
    }

    /**
     * Retourne le nombre de parties gagnées
     *
     * @return int|null
     */
    public function getGamesWon(): ?int
    {
        return $this->gamesWon;
    }

    /**
     * Modifie le nombre de parties gagnées
     *
     * @param int|null $gamesWon
     */
    public function setGamesWon(?int $gamesWon): void
    {
        $this->gamesWon = $gamesWon;
    }

    /**
     * Retourne le nombre de parties hébergées
     *
     * @return int|null
     */
    public function getGamesHosted(): ?int
    {
        return $this->gamesHosted;
    }

    /**
     * Modifie le nombre de parties hébergées
     *
     * @param int|null $gamesHosted
     */
    public function setGamesHosted(?int $gamesHosted): void
    {
        $this->gamesHosted = $gamesHosted;
    }
}