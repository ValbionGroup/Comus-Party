<?php
/**
 * @brief Classe de test pour la classe GameRecordDAO
 *
 * @file GameRecordDAOTest.php
 * @author Lucas ESPIET "espiet.l@valbion.com"
 * @version 1.0
 * @date 2024-12-20
 */

require_once __DIR__ . '/../include.php';

use ComusParty\Models\Db;
use ComusParty\Models\GameRecordDAO;
use ComusParty\Models\GameRecordState;
use PHPUnit\Framework\TestCase;

/**
 * @brief Classe GameRecordDAOTest
 * @details La classe GameRecordDAOTest permet de tester les méthodes de la classe GameRecordDAO
 */
class GameRecordDAOTest extends TestCase
{
    /**
     * @brief Instance de GameRecordDAO pour les tests
     * @var GameRecordDAO $gameRecordDAO Instance de GameRecordDAO pour les tests
     */
    private GameRecordDAO $gameRecordDAO;

    /**
     * @brief Instanciation d'un objet GameRecordDAO pour les tests
     * @return void
     */
    public function setUp(): void
    {
        $this->gameRecordDAO = new GameRecordDAO(Db::getInstance()->getConnection());
    }

    /**
     * @brief Test de la méthode findByState avec un état valide
     * @return void
     * @throws Exception
     */
    public function testFindByStateOk(): void
    {
        $this->assertEquals("game_rec_uuid1", $this->gameRecordDAO->findByState(GameRecordState::STARTED)[0]->getUuid());
    }

    /**
     * @brief Test de la méthode findByUuid avec un uuid valide
     * @return void
     * @throws Exception
     */
    public function testFindByUuidOk()
    {
        $this->assertEquals(GameRecordState::STARTED, $this->gameRecordDAO->findByUuid("game_rec_uuid1")->getState());
    }

    /**
     * @brief Test de la méthode findByHosterUuid avec un uuid valide
     * @return void
     * @throws Exception
     */
    public function testFindByHosterUuidOk()
    {
        $this->assertEquals("game_rec_uuid1", $this->gameRecordDAO->findByHostUuid("uuid1")[0]->getUuid());
    }

    /**
     * @brief Test de la méthode getPdo
     * @return void
     * @throws Exception
     */
    public function testGetPdo()
    {
        $this->assertEquals(Db::getInstance()->getConnection(), $this->gameRecordDAO->getPdo());
    }

    /**
     * @brief Test de la méthode setPdo
     * @return void
     * @throws Exception
     */
    public function testSetPdoOk()
    {
        $this->gameRecordDAO->setPdo(Db::getInstance()->getConnection());
        $this->assertEquals($this->gameRecordDAO->getPdo(), Db::getInstance()->getConnection());
    }

    /**
     * @brief Test de la méthode findByGameId avec un id de jeu valide
     * @return void
     * @throws Exception
     */
    public function testFindByGameIdOk()
    {
        $this->assertEquals("game_rec_uuid1", $this->gameRecordDAO->findByGameId(1)[0]->getUuid());
    }

    /**
     * @brief Test de la méthode __construct
     * @return void
     * @throws Exception
     */
    public function test__construct()
    {
        $this->assertInstanceOf(GameRecordDAO::class, $this->gameRecordDAO);
    }
}
