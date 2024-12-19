<?php

/**
 * @file ModeratorTest.php
 * @author Conchez-Boueytou Robin
 * @brief Le fichier contient la déclaration et la définition de la classe ModeratorTest
 * @details
 * @date 19/12/2024
 * @version 0.1
 */

use ComusParty\Models\Moderator;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../include.php';

class ModeratorTest extends TestCase
{
    /**
     * @brief Moderator
     * @var Moderator
     */
    private Moderator $moderator;

    /**
     * @brief Test de la méthode getUuid
     * @return void
     */
    public function testGetUuid(): void
    {
        $this->assertEquals('mod_uuid1', $this->moderator->getUuid());
    }

    /**
     * @brief Test de la méthode getUserId
     * @return void
     */
    public function testGetUserId(): void
    {
        $this->assertEquals(1, $this->moderator->getUserId());
    }

    /**
     * @brief Test de la méthode getFirstName
     * @return void
     */
    public function testGetFirstName(): void
    {
        $this->assertEquals('john', $this->moderator->getFirstName());
    }

    /**
     * @brief Test de la méthode getLastName
     * @return void
     */
    public function testGetLastName(): void
    {
        $this->assertEquals('doe', $this->moderator->getLastName());
    }

    /**
     * @brief Test de la méthode getCreatedAt
     * @return void
     */
    public function testGetCreatedAt(): void
    {
        $this->assertEquals(new DateTime('2024-01-01'), $this->moderator->getCreatedAt());
    }

    /**
     * @brief Test de la méthode getUpdatedAt
     * @return void
     */
    public function testGetUpdatedAt(): void
    {
        $this->assertEquals(new DateTime('2024-11-14'), $this->moderator->getUpdatedAt());
    }

    /**
     * @brief Test de la méthode setUuid avec un paramètre valide
     * @return void
     */
    public function testSetUuidWithValidUuid(): void
    {
        $this->moderator->setUuid('mod_uuid2');
        $this->assertEquals('mod_uuid2', $this->moderator->getUuid());
    }

    /**
     * @brief Test de la méthode setUuid avec un paramètre null
     * @return void
     */
    public function testSetUuidWithNullUuid(): void
    {
        $this->moderator->setUuid(null);
        $this->assertEquals(null, $this->moderator->getUuid());
    }

    /**
     * @brief Test de la méthode setUserId avec un paramètre valide
     * @return void
     */
    public function testSetUserIdWithValidUserId(): void
    {
        $this->moderator->setUserId(2);
        $this->assertEquals(2, $this->moderator->getUserId());
    }

    /**
     * @brief Test de la méthode setUserId avec un paramètre invalide
     * @return void
     */
    public function testSetUserIdWithInvalidUserId(): void
    {
        $this->expectException(TypeError::class);
        $this->moderator->setUserId('id1');
    }

    /**
     * @brief Test de la méthode setUserId avec un paramètre null
     * @return void
     */
    public function testSetUserIdWithNullUserId(): void
    {
        $this->moderator->setUserId(null);
        $this->assertEquals(null, $this->moderator->getUserId());
    }

    /**
     * @brief Test de la méthode setFirstName avec un paramètre valide
     * @return void
     */
    public function testSetFirstNameWithValidFirstName(): void
    {
        $this->moderator->setFirstName('jane');
        $this->assertEquals('jane', $this->moderator->getFirstName());
    }


    /**
     * @brief Test de la méthode setFirstName avec un paramètre null
     * @return void
     */
    public function testSetFirstNameWithNullFirstName(): void
    {
        $this->moderator->setFirstName(null);
        $this->assertEquals(null, $this->moderator->getFirstName());
    }

    /**
     * @brief Test de la méthode setLastName avec un paramètre valide
     * @return void
     */
    public function testSetLastNameWithValidLastName(): void
    {
        $this->moderator->setLastName('smith');
        $this->assertEquals('smith', $this->moderator->getLastName());
    }

    /**
     * @brief Test de la méthode setLastName avec un paramètre null
     * @return void
     */
    public function testSetLastNameWithNullLastName(): void
    {
        $this->moderator->setLastName(null);
        $this->assertEquals(null, $this->moderator->getLastName());
    }

    /**
     * @brief Test de la méthode setCreatedAt avec un paramètre valide
     * @return void
     */
    public function testSetCreatedAtWithValidCreatedAt(): void
    {
        $this->moderator->setCreatedAt(new DateTime('2024-01-02'));
        $this->assertEquals(new DateTime('2024-01-02'), $this->moderator->getCreatedAt());
    }

    /**
     * @brief Test de la méthode setCreatedAt avec un paramètre invalide
     * @return void
     */
    public function testSetCreatedAtWithInvalidCreatedAt(): void
    {
        $this->expectException(TypeError::class);
        $this->moderator->setCreatedAt('2024-01-02');
    }

    /**
     * @brief Test de la méthode setCreatedAt avec un paramètre null
     * @return void
     */
    public function testSetCreatedAtWithNullCreatedAt(): void
    {
        $this->moderator->setCreatedAt(null);
        $this->assertEquals(null, $this->moderator->getCreatedAt());
    }

    /**
     * @brief Test de la méthode setUpdatedAt avec un paramètre valide
     * @return void
     */
    public function testSetUpdatedAtWithValidUpdatedAt(): void
    {
        $this->moderator->setUpdatedAt(new DateTime('2024-11-15'));
        $this->assertEquals(new DateTime('2024-11-15'), $this->moderator->getUpdatedAt());
    }

    /**
     * @brief Test de la méthode setUpdatedAt avec un paramètre invalide
     * @return void
     */
    public function testSetUpdatedAtWithInvalidUpdatedAt(): void
    {
        $this->expectException(TypeError::class);
        $this->moderator->setUpdatedAt('2024-11-15');
    }

    /**
     * @brief Test de la méthode setUpdatedAt avec un paramètre null
     * @return void
     */
    public function testSetUpdatedAtWithNullUpdatedAt(): void
    {
        $this->moderator->setUpdatedAt(null);
        $this->assertEquals(null, $this->moderator->getUpdatedAt());
    }

    /**
     * @brief Instanciation d'un objet Moderator pour les tests
     * @return void
     */
    protected function setUp(): void
    {
        $this->moderator = new Moderator(
            uuid: 'mod_uuid1',
            userId: 1,
            firstName: 'john',
            lastName: 'doe',
            createdAt: new DateTime('2024-01-01'),
            updatedAt: new DateTime('2024-11-14')
        );
    }


}