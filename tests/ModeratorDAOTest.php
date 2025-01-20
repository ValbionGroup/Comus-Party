<?php

/**
 * @file ModeratorDAOTest.php
 * @author Conchez-Boueytou Robin
 * @brief Le fichier contient la déclaration et la définition de la classe ModeratorDAOTest
 * @date 19/12/2024
 * @version 0.1
 */

use ComusParty\App\Db;
use ComusParty\Models\Moderator;
use ComusParty\Models\ModeratorDAO;
use PHPUnit\Framework\TestCase;

include_once __DIR__ . '/../include.php';

class ModeratorDAOTest extends TestCase
{
    /**
     * @brief ModeratorDAO
     * @var ModeratorDAO
     */
    private ModeratorDAO $moderatorDAO;

    /**
     * @brief Test de la méthode getPdo
     * @return void
     */
    public function testGetPdo(): void
    {
        $this->assertInstanceOf(PDO::class, $this->moderatorDAO->getPdo());
    }

    /**
     * @brief Test de la méthode setPdo avec un objet PDO invalide
     * @return void
     */
    public function testSetPdoWithValidPdo(): void
    {
        $this->moderatorDAO->setPdo(Db::getInstance()->getConnection());
        $this->assertInstanceOf(PDO::class, $this->moderatorDAO->getPdo());
    }

    /**
     * @brief Test de la méthode setPdo avec un objet PDO invalide
     * @return void
     */
    public function testSetPdoWithInvalidPdo(): void
    {
        $this->expectException(TypeError::class);
        $this->moderatorDAO->setPdo('invalid');
    }

    /**
     * @brief Test de la méthode setPdo avec un objet PDO null
     * @return void
     */
    public function testSetPdoWithNullPdo(): void
    {
        $this->moderatorDAO->setPdo(null);
        $this->assertNull($this->moderatorDAO->getPdo());
    }

    /**
     * @brief Test de la méthode hydrate avec un tableau invalide
     * @return void
     * @throws DateMalformedStringException
     */
    public function testHydrateWithValidArray(): void
    {
        $moderator = $this->moderatorDAO->hydrate([
            'uuid' => 'mod_uuid1',
            'user_id' => 1,
            'first_name' => 'john',
            'last_name' => 'doe',
            'created_at' => "2024-01-01",
            'updated_at' => "2024-01-02"
        ]);
        $this->assertEquals('mod_uuid1', $moderator->getUuid());
        $this->assertEquals(1, $moderator->getUserId());
        $this->assertEquals('john', $moderator->getFirstName());
        $this->assertEquals('doe', $moderator->getLastName());
        $this->assertEquals(new DateTime("2024-01-01"), $moderator->getCreatedAt());
        $this->assertEquals(new DateTime("2024-01-02"), $moderator->getUpdatedAt());
    }

    /**
     * @brief Test de la méthode hydrate avec un tableau invalide
     * @return void
     */
    public function testHydrateWithInvalidArray(): void
    {
        $this->expectException(TypeError::class);
        $moderator = $this->moderatorDAO->hydrate([
            'uuid' => 1,
            'user_id' => 'id2',
            'first_name' => 'john',
            'last_name' => 'doe',
            'created_at' => "2024-01-01",
            'updated_at' => "2024-01-02"
        ]);
        $this->assertEquals(1, $moderator->getUuid());
        $this->assertEquals('id2', $moderator->getUserId());
        $this->assertEquals('john', $moderator->getFirstName());
        $this->assertEquals('doe', $moderator->getLastName());
        $this->assertEquals(new DateTime("2024-01-01"), $moderator->getCreatedAt());
        $this->assertEquals(new DateTime("2024-01-02"), $moderator->getUpdatedAt());
    }

    /**
     * @brief Test de la méthode hydrate avec un type invalide
     * @return void
     * @throws DateMalformedStringException
     */
    public function testHydrateWithInvalidType(): void
    {
        $this->expectException(TypeError::class);
        $this->moderatorDAO->hydrate('invalid');
    }

    /**
     * @brief Test de la méthode hydrateMany avec un tableau invalide
     * @return void
     * @throws DateMalformedStringException
     */
    public function testHydrateManyWithValidArray(): void
    {
        $moderators = $this->moderatorDAO->hydrateMany([
            [
                'uuid' => 'mod_uuid1',
                'user_id' => 1,
                'first_name' => 'john',
                'last_name' => 'doe',
                'created_at' => "2024-01-01",
                'updated_at' => "2024-01-02"
            ],
            [
                'uuid' => 'mod_uuid2',
                'user_id' => 2,
                'first_name' => 'jane',
                'last_name' => 'doe',
                'created_at' => "2024-01-01",
                'updated_at' => "2024-01-02"
            ]
        ]);
        $this->assertEquals('mod_uuid1', $moderators[0]->getUuid());
        $this->assertEquals(1, $moderators[0]->getUserId());
        $this->assertEquals('john', $moderators[0]->getFirstName());
        $this->assertEquals('doe', $moderators[0]->getLastName());
        $this->assertEquals(new DateTime("2024-01-01"), $moderators[0]->getCreatedAt());
        $this->assertEquals(new DateTime("2024-01-02"), $moderators[0]->getUpdatedAt());

        $this->assertEquals('mod_uuid2', $moderators[1]->getUuid());
        $this->assertEquals(2, $moderators[1]->getUserId());
        $this->assertEquals('jane', $moderators[1]->getFirstName());
        $this->assertEquals('doe', $moderators[1]->getLastName());
        $this->assertEquals(new DateTime("2024-01-01"), $moderators[1]->getCreatedAt());
        $this->assertEquals(new DateTime("2024-01-02"), $moderators[1]->getUpdatedAt());
    }

    /**
     * @brief Test de la méthode hydrateMany avec un tableau invalide
     * @return void
     * @throws DateMalformedStringException
     */
    public function testHydrateManyWithInvalidArray(): void
    {
        $this->expectException(TypeError::class);
        $moderators = $this->moderatorDAO->hydrateMany([
            [
                'uuid' => 1,
                'user_id' => 'id1',
                'first_name' => 'john',
                'last_name' => 'doe',
                'created_at' => "2024-01-01",
                'updated_at' => "2024-01-02"
            ],
            [
                'uuid' => 2,
                'user_id' => 'id2',
                'first_name' => 'jane',
                'last_name' => 'doe',
                'created_at' => "2024-01-01",
                'updated_at' => "2024-01-02"
            ]
        ]);
        $this->assertEquals(1, $moderators[0]->getUuid());
        $this->assertEquals('id1', $moderators[0]->getUserId());
        $this->assertEquals('john', $moderators[0]->getFirstName());
        $this->assertEquals('doe', $moderators[0]->getLastName());
        $this->assertEquals(new DateTime("2024-01-01"), $moderators[0]->getCreatedAt());
        $this->assertEquals(new DateTime("2024-01-02"), $moderators[0]->getUpdatedAt());

        $this->assertEquals(2, $moderators[1]->getUuid());
        $this->assertEquals('id2', $moderators[1]->getUserId());
        $this->assertEquals('jane', $moderators[1]->getFirstName());
        $this->assertEquals('doe', $moderators[1]->getLastName());
        $this->assertEquals(new DateTime("2024-01-01"), $moderators[1]->getCreatedAt());
        $this->assertEquals(new DateTime("2024-01-02"), $moderators[1]->getUpdatedAt());
    }

    /**
     * @brief Test de la méthode hydrateMany avec un type invalide
     * @return void
     * @throws DateMalformedStringException
     */
    public function testHydrateManyWithInvalidType(): void
    {
        $this->expectException(TypeError::class);
        $this->moderatorDAO->hydrateMany('invalid');
    }

    /**
     * @brief Test de la méthode findByUuid avec un Uuid modérateur valide
     * @return void
     * @throws DateMalformedStringException
     */
    public function testFindByIdWithValidUuid(): void
    {
        $this->assertInstanceOf(Moderator::class, $this->moderatorDAO->findByUuid("mod_uuid1"));
    }

    /**
     * @brief Test de la méthode findByUuid avec un Uuid modérateur invalide
     * @return void
     * @throws DateMalformedStringException
     */
    public function testFindByIdWithInvalidUuid(): void
    {
        $this->assertNull($this->moderatorDAO->findByUuid("invalid"));
    }

    /**
     * @brief Test de la méthode findByUuid avec un Uuid modérateur null
     * @return void
     * @throws DateMalformedStringException
     */
    public function testFindByUuidWithNullUuid(): void
    {
        $this->expectException(TypeError::class);
        $this->moderatorDAO->findByUuid(null);
    }

    /**
     * @brief Test de la méthode findByUserId avec un identifiant utilisateur valide
     * @return void
     * @throws DateMalformedStringException
     */
    public function testFindByUserIdWithValidUserId(): void
    {
        $this->assertInstanceOf(Moderator::class, $this->moderatorDAO->findByUserId(1));
    }

    /**
     * @brief Test de la méthode findByUserId avec un identifiant utilisateur invalide
     * @return void
     * @throws DateMalformedStringException
     */
    public function testFindByUserIdWithInvalidUserId(): void
    {
        $this->assertNull($this->moderatorDAO->findByUserId(0));
    }

    /**
     * @brief Test de la méthode findByUserId avec un identifiant utilisateur null
     * @return void
     * @throws DateMalformedStringException
     */
    public function testFindByUserIdWithNullUserId(): void
    {
        $this->expectException(TypeError::class);
        $this->moderatorDAO->findByUserId(null);
    }

    /**
     * @brief Instanciation d'un objet ModeratorDAO pour les tests
     * @return void
     */
    protected function setUp(): void
    {
        $this->moderatorDAO = new ModeratorDAO(Db::getInstance()->getConnection());
    }

}
