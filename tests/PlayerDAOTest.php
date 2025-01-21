<?php
/**
 * @file controllerTest.php
 * @brief Le fichier contient la déclaration et la définition de la classe PlayerDAOTest
 * @author Conchez-Boueytou Robin
 * @date 14/11/2024
 * @version 0.1
 */
require_once __DIR__ . '/../include.php';

use ComusParty\App\Db;
use ComusParty\Models\PlayerDAO;
use PHPUnit\Framework\TestCase;

/**
 * @brief Classe PlayerDAOTest
 * @details La classe PlayerDAOTest permet de tester les méthodes de la classe PlayerDAO
 */
class PlayerDAOTest extends TestCase
{
    /**
     * @brief PlayerDAO
     *
     * @var PlayerDAO
     */
    private PlayerDAO $playerDAO;

    /**
     * @brief Test de la méthode getPlayerDAO
     * @return void
     */
    public function testGetPlayerDAO(): void
    {
        $this->assertInstanceOf(PlayerDAO::class, $this->playerDAO);
    }

    /**
     * @brief Test de la méthode setPlayerDAO
     * @return void
     */
    public function testSetPlayerDAO(): void
    {
        $this->playerDAO = new PlayerDAO(Db::getInstance()->getConnection());
        $this->assertInstanceOf(PlayerDAO::class, $this->playerDAO);
    }

    /**
     * @brief Test de la méthode findByUuid
     * @return void
     */
    public function testFindByUuid(): void
    {
        $this->assertEquals('JohnDoe', $this->playerDAO->findByUuid('uuid1')->getUsername());
    }

    /**
     * @brief Test de la méthode findByUserId
     * @return void
     */
    public function testFindByUserId(): void
    {
        $this->assertEquals('JohnDoe', $this->playerDAO->findByUserId(1)->getUsername());
    }

    /**
     * @brief Test de la méthode findWithDetailByUuid
     * @return void
     * @throws DateMalformedStringException
     */
    public function testFindWithDetailByUuid(): void
    {
        $this->assertEquals('JohnDoe', $this->playerDAO->findWithDetailByUuid('uuid1')->getUsername());
    }

    /**
     * @brief Test de la méthode findWithDetailByUserId
     * @return void
     * @throws DateMalformedStringException
     */
    public function testFindWithDetailByUserId(): void
    {
        $this->assertEquals('JohnDoe', $this->playerDAO->findWithDetailByUserId(1)->getUsername());
    }

    /**
     * @brief Test de la méthode findAll
     * @return void
     * @throws DateMalformedStringException
     */
    public function testFindAll(): void
    {
        $this->assertEquals('JohnDoe', $this->playerDAO->findAll()[0]->getUsername());
    }

    /**
     * @brief Test de la méthode testFindALLWithDetail
     * @return void
     * @throws DateMalformedStringException
     */
    public function testFindAllWithDetail(): void
    {
        $this->assertEquals('JohnDoe', $this->playerDAO->findAllWithDetail()[0]->getUsername());
    }

    /**
     * @brief Test de la méthode Hydrate
     * @return void
     * @throws DateMalformedStringException
     */
    public function testHydrate(): void
    {
        $player = $this->playerDAO->hydrate([
            'uuid' => 'uuid-player1',
            'username' => 'PlayerOne',
            'created_at' => '2024-01-01',
            'updated_at' => '2024-11-14',
            'elo' => 100,
            'xp' => 100,
            'level' => 1,
            'rank' => 1,
            'comus_coins' => 50,
            'games_played' => 10,
            'games_won' => 5,
            'games_hosted' => 5,
            'user_id' => 1]);

        $this->assertEquals('PlayerOne', $player->getUsername());
    }

    /**
     * @brief Test de la méthode HydrateMany
     * @return void
     * @throws DateMalformedStringException
     */
    public function testHydrateMany(): void
    {
        $player = $this->playerDAO->hydrateMany([[
            'uuid' => 'uuid-player1',
            'username' => 'PlayerOne',
            'created_at' => '2024-01-01',
            'updated_at' => '2024-11-14',
            'elo' => 100,
            'xp' => 100,
            'level' => 1,
            'rank' => 1,
            'comus_coins' => 50,
            'games_played' => 10,
            'games_won' => 5,
            'games_hosted' => 5,
            'user_id' => 1],
            ['uuid' => 'uuid-player2',
                'username' => 'PlayerTwo',
                'created_at' => '2024-01-01',
                'updated_at' => '2024-11-14',
                'elo' => 100,
                'xp' => 100,
                'level' => 1,
                'rank' => 1,
                'comus_coins' => 50,
                'games_played' => 10,
                'games_won' => 5,
                'games_hosted' => 5,
                'user_id' => 2]]);

        $this->assertEquals('PlayerOne', $player[0]->getUsername());
        $this->assertEquals('PlayerTwo', $player[1]->getUsername());
    }

    protected function setUp(): void
    {
        /**
         * @brief Instanciation d'un objet PlayerDAO pour les tests
         */
        $this->playerDAO = new PlayerDAO(Db::getInstance()->getConnection());
    }
}