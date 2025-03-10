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
use Random\RandomException;

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
            true,
            hash('sha256', 'tkn1'),
            new DateTime('2024-01-01'),
            new DateTime('2024-11-14'),
            new DateTime('2024-12-19')
        );
    }

    /**
     * @brief Test de la méthode getUuid
     * @return void
     */
    public function testGetCodeOk(): void
    {
        $this->assertEquals('uuid1', $this->gameRecord->getCode());
    }

    /**
     * @brief Test de la méthode getToken
     * @return void
     */
    public function testGetTokenOk(): void
    {
        $this->assertEquals(hash('sha256', 'tkn1'), $this->gameRecord->getToken());
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
     * @brief Test de la méthode isPrivate
     * @return void
     */
    public function testIsPrivateOk(): void
    {
        $this->assertTrue($this->gameRecord->isPrivate());
    }

    /**
     * @brief Test de la méthode setPrivate avec un paramètre valide
     * @return void
     */
    public function testSetPrivateOk(): void
    {
        $this->gameRecord->setPrivate(false);
        $this->assertFalse($this->gameRecord->isPrivate());
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
     * @brief Test de la méthode setToken avec un paramètre valide
     * @return void
     */
    public function testSetTokenOk(): void
    {
        $this->gameRecord->setToken(hash('sha256', 'tkn2'));
        $this->assertEquals(hash('sha256', 'tkn2'), $this->gameRecord->getToken());
    }

    /**
     * @brief Test de la méthode setToken avec un paramètre invalide
     * @return void
     */
    public function testSetTokenWithInvalidToken(): void
    {
        $this->expectException(TypeError::class);
        $this->gameRecord->setToken();
    }

    /**
     * @brief Test de la méthode generateToken
     * @return void
     * @throws RandomException
     */
    public function testGenerateTokenOk(): void
    {
        $generatedToken = $this->gameRecord->generateToken();
        $this->assertNotNull($this->gameRecord->getToken());
        $this->assertEquals(hash('sha256', $generatedToken), $this->gameRecord->getToken());
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
    public function testSetCodeOk(): void
    {
        $this->gameRecord->setCode('uuid2');
        $this->assertEquals('uuid2', $this->gameRecord->getCode());
    }

    /**
     * @brief Test de la méthode setUuid avec un paramètre invalide
     * @return void
     */
    public function testSetUuidWithInvalidUuid(): void
    {
        $this->expectException(TypeError::class);
        $this->gameRecord->setCode();
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

    /**
     * @brief Test de la méthode addPlayer avec un paramètre valide
     * @return void
     */
    public function testAddPlayerOk(): void
    {
        $player = new Player(
            'uuid2',
            'username2',
            null,
            null,
            null,
            null,
            null,
            null,
            null,
        );
        $this->gameRecord->addPlayer($player);
        $this->assertEquals([new Player(), new Player(), $player], $this->gameRecord->getPlayers());
        $this->gameRecord->removePlayer($player);
    }

    /**
     * @brief Test de la méthode addPlayer avec un paramètre invalide
     * @return void
     */
    public function testAddPlayerWithInvalidPlayer(): void
    {
        $this->expectException(TypeError::class);
        $this->gameRecord->addPlayer('player');
    }

    /**
     * @brief Test de la méthode removePlayer avec un paramètre valide
     * @return void
     */
    public function testRemovePlayerOk(): void
    {
        $player = new Player(
            'uuid2',
            'username2',
            null,
            null,
            null,
            null,
            null,
            null,
            null,
        );
        $this->gameRecord->addPlayer($player);
        $this->gameRecord->removePlayer($player);
        $this->assertEquals([new Player(), new Player()], $this->gameRecord->getPlayers());
    }

    /**
     * @brief Test de la méthode removePlayer avec un paramètre invalide
     * @return void
     */
    public function testRemovePlayerWithInvalidPlayer(): void
    {
        $this->expectException(TypeError::class);
        $this->gameRecord->removePlayer('player');
    }
}
