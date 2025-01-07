<?php
/**
 * @file DbTest.php
 * @brief Fichier de test de la base de données
 * @author Lucas ESPIET "lespiet@iutbayonne.univ-pau.fr"
 * @date 2024-11-18
 * @version 1.1
 */

require_once __DIR__ . '/../include.php';

use ComusParty\Models\Db;
use PHPUnit\Framework\TestCase;

/**
 * @brief Class DbTest
 */
class DbTest extends TestCase
{
    /**
     * @brief Test de la méthode getInstance
     *
     * @throws Exception
     */
    public function testGetConnection()
    {
        $db = Db::getInstance();
        $this->assertInstanceOf(PDO::class, $db->getConnection());
    }

    /**
     * @brief Test de l'unicité du singleton
     *
     * @throws Exception
     */
    public function testSingletonSame()
    {
        $db1 = Db::getInstance();
        $db2 = Db::getInstance();
        $this->assertSame($db1, $db2);
    }

    /**
     * @brief Test de la désérialisation du singleton
     *
     * @throws Exception
     */
    public function testSingletonDeserialize()
    {
        $this->expectException(Exception::class);
        $db1 = Db::getInstance();
        $serialized = serialize($db1);
        $db2 = unserialize($serialized);
        $this->assertSame($db1, $db2);
    }
}