<?php
/**
 * @file PlayerTest.php
 * @brief Le fichier contient la déclaration et la définition de la classe GameDao
 * @author Conchez-Boueytou Robin
 * @date 14/11/2024
 * @version 0.1
 */

require_once __DIR__ . '/../include.php';

use ComusParty\Models\Player;
use PHPUnit\Framework\TestCase;
/**
 * @brief Classe PlayerTest
 * @details La classe PlayerTest permet de tester les méthodes de la classe Player
 */
class PlayerTest extends TestCase
{
    /**
     * @brief Player
     *
     * @var Player
     */
    private Player $player;
    protected function setUp(): void
    {
        /**
         * @brief Instanciation d'un objet Player pour les tests
         */

        $this->player = new Player(
            uuid: '123e4567-e89b-12d3-a456-426614174000',
            username: 'TestUser',
            createdAt: new DateTime('2024-01-01'),
            updatedAt: new DateTime('2024-11-14'),
            xp: 100,
            elo: 1500,
            comusCoins: 50,
            statistics: null, // Vous pouvez utiliser un mock pour les statistiques
            userId: 42
        );
    }
    /**
     * @brief Test de la méthode getId
     * @return void
     */
    public function testGetUuid(): void
    {
        $this->assertEquals('123e4567-e89b-12d3-a456-426614174000', $this->player->getUuid());
    }
    /**
     * @brief Test de la méthode setId
     * @return void
     */
    public function testSetUuid(): void
    {
        $this->player->setUuid('123e4567-e89b-12d3-a456-426614174001');
        $this->assertEquals('123e4567-e89b-12d3-a456-426614174001', $this->player->getUuid());
    }
    /**
     * @brief Test de la méthode getUsername
     * @return void
     */
    public function testGetUsername(): void
    {
        $this->assertEquals('TestUser', $this->player->getUsername());
    }
    /**
     * @brief Test de la méthode setUsername
     * @return void
     */
    public function testSetUsername(): void
    {
        $this->player->setUsername('TestUser2');
        $this->assertEquals('TestUser2', $this->player->getUsername());
    }
    /**
     * @brief Test de la méthode getCreatedAt
     * @return void
     */
    public function testGetCreatedAt(): void
    {
        $this->assertEquals(new DateTime('2024-01-01'), $this->player->getCreatedAt());
    }
    /**
     * @brief Test de la méthode setCreatedAt
     * @return void
     */
    public function testSetCreatedAt(): void
    {
        $this->player->setCreatedAt(new DateTime('2024-01-02'));
        $this->assertEquals(new DateTime('2024-01-02'), $this->player->getCreatedAt());
    }
    /**
     * @brief Test de la méthode getUpdatedAt
     * @return void
     */
    public function testGetUpdatedAt(): void
    {
        $this->assertEquals(new DateTime('2024-11-14'), $this->player->getUpdatedAt());
    }
    /**
     * @brief Test de la méthode setUpdatedAt
     * @return void
     */
    public function testSetUpdatedAt(): void
    {
        $this->player->setUpdatedAt(new DateTime('2024-11-15'));
        $this->assertEquals(new DateTime('2024-11-15'), $this->player->getUpdatedAt());
    }
    /**
     * @brief Test de la méthode getElo
     * @return void
     */
    public function testGetXp(): void
    {
        $this->assertEquals(100, $this->player->getXp());
    }
    /**
     * @brief Test de la méthode setElo
     * @return void
     */
    public function testSetXp(): void
    {
        $this->player->setXp(200);
        $this->assertEquals(200, $this->player->getXp());
    }
}
