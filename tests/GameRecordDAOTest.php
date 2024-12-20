<?php
/**
 * @brief Classe de test pour la classe GameRecordDAO
 *
 * @file GameRecordDAOTest.php
 * @author Lucas ESPIET "espiet.l@valbion.com"
 * @version 1.0
 * @date 2024-12-20
 */

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
        $this->assertEquals(1, $this->gameRecordDAO->findByState(GameRecordState::WAITING)[0]->getId());
    }

    public function testFindByUuid()
    {

    }

    public function testFindByHosterUuid()
    {

    }

    public function testSetPdo()
    {

    }

    public function testFindByGameId()
    {

    }

    public function testGetPdo()
    {

    }

    public function test__construct()
    {

    }
}
