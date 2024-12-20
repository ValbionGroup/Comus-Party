<?php
/**
 * @brief Classe de test pour GameRecord
 *
 * @file GameRecordTest.php
 * @author Lucas ESPIET "espiet.l@valbion.com"
 * @version 1.0
 * @date 2024-12-19
 */

include_once __DIR__ . '/../include.php';

use ComusParty\Models\Game;
use ComusParty\Models\GameRecord;
use ComusParty\Models\GameRecordState;
use ComusParty\Models\Player;
use PHPUnit\Framework\TestCase;

/**
 * @brief Classe GameRecordTest
 * @details La classe GameRecordTest permet de tester les méthodes de la classe GameRecord
 */
class GameRecordTest extends TestCase
{
    /**
     * @brief Instance de GameRecord pour les tests
     * @var GameRecord $gameRecord Instance de GameRecord pour les tests
     */
    private GameRecord $gameRecord;

    /**
     * @brief Instanciation d'un objet GameRecord pour les tests
     * @return void
     */
    public function setUp(): void
    {
        $this->gameRecord = new GameRecord(
            'uuid1',
            new Game(),
            new Player(),
            [new Player(), new Player()],
            GameRecordState::WAITING,
            new DateTime('2024-01-01'),
            new DateTime('2024-11-14'),
            new DateTime('2024-12-19')
        );
    }

    /**
     * @brief Test de la méthode getUuid
     * @return void
     */
    public function testGetUuidOk(): void
    {
        $this->assertEquals('uuid1', $this->gameRecord->getUuid());
    }

    /**
     * @brief Test de la méthode getGame
     * @return void
     */
    public function testGetGameOk(): void
    {
        $this->assertEquals(new Game(), $this->gameRecord->getGame());
    }

    /**
     * @brief Test de la méthode getState
     * @return void
     */
    public function testGetStateOk(): void
    {
        $this->assertEquals(GameRecordState::WAITING, $this->gameRecord->getState());
    }

    /**
     * @brief Test de la méthode getCreatedAt
     * @return void
     */
    public function testGetCreatedAtOk(): void
    {
        $this->assertEquals(new DateTime('2024-01-01'), $this->gameRecord->getCreatedAt());
    }

    /**
     * @brief Test de la méthode getUpdatedAt
     * @return void
     */
    public function testGetUpdatedAtOk(): void
    {
        $this->assertEquals(new DateTime('2024-11-14'), $this->gameRecord->getUpdatedAt());
    }

    /**
     * @brief Test de la méthode getFinishedAt
     * @return void
     */
    public function testGetFinishedAtOk(): void
    {
        $this->assertEquals(new DateTime('2024-12-19'), $this->gameRecord->getFinishedAt());
    }

    /**
     * @brief Test de la méthode getHostedBy
     * @return void
     */
    public function testGetHostedByOk(): void
    {
        $this->assertEquals(new Player(), $this->gameRecord->getHostedBy());
    }

    /**
     * @brief Test de la méthode getPlayers
     * @return void
     */
    public function testGetPlayersOk(): void
    {
        $this->assertEquals([new Player(), new Player()], $this->gameRecord->getPlayers());
    }

    /**
     * @brief Test de la méthode setFinishedAt avec un paramètre valide
     * @return void
     */
    public function testSetFinishedAtOk(): void
    {
        $this->gameRecord->setFinishedAt(new DateTime('2024-12-20'));
        $this->assertEquals(new DateTime('2024-12-20'), $this->gameRecord->getFinishedAt());
    }

    /**
     * @brief Test de la méthode setFinishedAt avec un paramètre invalide
     * @return void
     */
    public function testSetFinishedAtWithInvalidFinishedAt(): void
    {
        $this->expectException(TypeError::class);
        $this->gameRecord->setFinishedAt('2024-12-20');
    }

    /**
     * @brief Test de la méthode setUpdatedAt avec un paramètre valide
     * @return void
     */
    public function testSetUpdatedAtOk(): void
    {
        $this->gameRecord->setUpdatedAt(new DateTime('2024-11-20'));
        $this->assertEquals(new DateTime('2024-11-20'), $this->gameRecord->getUpdatedAt());
    }

    /**
     * @brief Test de la méthode setUpdatedAt avec un paramètre invalide
     * @return void
     */
    public function testSetUpdatedAtWithInvalidUpdatedAt(): void
    {
        $this->expectException(TypeError::class);
        $this->gameRecord->setUpdatedAt('2024-11-20');
    }

    /**
     * @brief Test de la méthode setCreatedAt avec un paramètre valide
     * @return void
     */
    public function testSetPlayersOk(): void
    {
        $players = [new Player(), new Player(), new Player()];
        $this->gameRecord->setPlayers($players);
        $this->assertEquals($players, $this->gameRecord->getPlayers());
    }

    /**
     * @brief Test de la méthode setPlayers avec un paramètre invalide
     * @return void
     */
    public function testSetPlayersWithInvalidPlayers(): void
    {
        $this->expectException(TypeError::class);
        $this->gameRecord->setPlayers('players');
    }

    /**
     * @brief Test de la méthode setState avec un paramètre valide
     * @return void
     */
    public function testSetStateOk(): void
    {
        $this->gameRecord->setState(GameRecordState::STARTED);
        $this->assertEquals(GameRecordState::STARTED, $this->gameRecord->getState());
    }

    /**
     * @brief Test de la méthode setState avec un paramètre invalide
     * @return void
     */
    public function testSetStateWithInvalidState(): void
    {
        $this->expectException(TypeError::class);
        $this->gameRecord->setState('state');
    }

    /**
     * @brief Test de la méthode setGame avec un paramètre valide
     * @return void
     */
    public function testSetGameOk(): void
    {
        $game = new Game();
        $this->gameRecord->setGame($game);
        $this->assertEquals($game, $this->gameRecord->getGame());
    }

    /**
     * @brief Test de la méthode setGame avec un paramètre invalide
     * @return void
     */
    public function testSetGameWithInvalidGame(): void
    {
        $this->expectException(TypeError::class);
        $this->gameRecord->setGame('game');
    }

    /**
     * @brief Test de la méthode setHostedBy avec un paramètre valide
     * @return void
     */
    public function testSetHostedByOk(): void
    {
        $player = new Player();
        $this->gameRecord->setHostedBy($player);
        $this->assertEquals($player, $this->gameRecord->getHostedBy());
    }

    /**
     * @brief Test de la méthode setHostedBy avec un paramètre invalide
     * @return void
     */
    public function testSetHostedByWithInvalidHostedBy(): void
    {
        $this->expectException(TypeError::class);
        $this->gameRecord->setHostedBy('player');
    }

    /**
     * @brief Test de la méthode setUuid avec un paramètre invalide
     * @return void
     */
    public function testSetUuidOk(): void
    {
        $this->gameRecord->setUuid('uuid2');
        $this->assertEquals('uuid2', $this->gameRecord->getUuid());
    }

    /**
     * @brief Test de la méthode setUuid avec un paramètre invalide
     * @return void
     */
    public function testSetUuidWithInvalidUuid(): void
    {
        $this->expectException(TypeError::class);
        $this->gameRecord->setUuid();
    }

    /**
     * @brief Test de la méthode setCreatedAt avec un paramètre valide
     * @return void
     */
    public function testSetCreatedAtOk(): void
    {
        $this->gameRecord->setCreatedAt(new DateTime('2024-01-02'));
        $this->assertEquals(new DateTime('2024-01-02'), $this->gameRecord->getCreatedAt());
    }

    /**
     * @brief Test de la méthode setCreatedAt avec un paramètre invalide
     * @return void
     */
    public function testSetCreatedAtWithInvalidCreatedAt(): void
    {
        $this->expectException(TypeError::class);
        $this->gameRecord->setCreatedAt('2024-01-02');
    }
}
