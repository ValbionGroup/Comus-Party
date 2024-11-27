<?php

/**
 * @file StatisticsTest.php
 * @author Conchez-Boueytou Robin
 * @brief Le fichier contient la déclaration et la définition de la classe StatisticsTest
 * @date 14/11/2024
 * @version 1.0
 */

require_once __DIR__ . '/../include.php';

use ComusParty\Models\Statistics;
use PHPUnit\Framework\TestCase;

/**
 * @brief Classe StatisticsTest
 * @details La classe StatisticsTest permet de tester les méthodes de la classe Statistics
 */
class StatisticsTest extends TestCase
{
    /**
     * @brief Statistics
     *
     * @var Statistics
     */
    private Statistics $statistics;

    protected function setUp(): void
    {
        /**
         * @brief Instanciation d'un objet Statistics pour les tests
         */

        $this->statistics = new Statistics(
            playerUuid: '123e4567-e89b-12d3-a456-426614174000',
            gamesPlayed: 10,
            gamesWon: 5,
            gamesHosted: 2
        );
    }

    /**
     * @brief Test de la méthode getPlayerUuid
     * @return void
     */
    public function testGetPlayerUuid(): void
    {
        $this->assertEquals('123e4567-e89b-12d3-a456-426614174000', $this->statistics->getPlayerUuid());
    }

    /**
     * @brief Test de la méthode setPlayerUuid
     * @return void
     */
    public function testSetPlayerUuid(): void
    {
        $this->statistics->setPlayerUuid('123e4567-e89b-12d3-a456-426614174001');
        $this->assertEquals('123e4567-e89b-12d3-a456-426614174001', $this->statistics->getPlayerUuid());
    }

    /**
     * @brief Test de la méthode getGamesPlayed
     * @return void
     */
    public function testGetGamesPlayed(): void
    {
        $this->assertEquals(10, $this->statistics->getGamesPlayed());
    }

    /**
     * @brief Test de la méthode setGamesPlayed
     * @return void
     */
    public function testSetGamesPlayed(): void
    {
        $this->statistics->setGamesPlayed(20);
        $this->assertEquals(20, $this->statistics->getGamesPlayed());
    }

    /**
     * @brief Test de la méthode getGamesWon
     * @return void
     */
    public function testGetGamesWon(): void
    {
        $this->assertEquals(5, $this->statistics->getGamesWon());
    }

    /**
     * @brief Test de la méthode setGamesWon
     * @return void
     */
    public function testSetGamesWon(): void
    {
        $this->statistics->setGamesWon(10);
        $this->assertEquals(10, $this->statistics->getGamesWon());
    }
}
