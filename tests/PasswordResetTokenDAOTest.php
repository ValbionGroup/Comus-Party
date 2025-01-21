<?php
/**
 * @brief Classe de test PasswordResetTokenDAOTest pour tester les méthodes de la classe PasswordResetTokenDAO
 *
 * @file PasswordResetTokenDAOTest.php
 * @author Lucas ESPIET "espiet.l@valbion.com"
 * @version 1.0
 * @date 2024-12-17
 */

require_once __DIR__ . '/../include.php';

use ComusParty\App\Db;
use ComusParty\Models\PasswordResetToken;
use ComusParty\Models\PasswordResetTokenDAO;
use PHPUnit\Framework\TestCase;

/**
 * @brief Classe PasswordResetTokenDAOTest
 * @details La classe PasswordResetTokenDAOTest permet de tester les méthodes de la classe PasswordResetTokenDAO
 */
class PasswordResetTokenDAOTest extends TestCase
{
    /**
     * @brief Classe PasswordResetTokenDAO
     * @var PasswordResetTokenDAO $passwordResetTokenDAO
     */
    private PasswordResetTokenDAO $passwordResetTokenDAO;

    /**
     * @brief Test de la méthode findByUserId avec un paramètre valide
     * @return void
     * @throws DateMalformedStringException Exception levée si la date est mal formée
     */
    public function testFindByUserIdOk(): void
    {
        $this->assertEquals(1, $this->passwordResetTokenDAO->findByUserId(1)->getUserId());
    }

    /**
     * @brief Test de la méthode delete avec un paramètre valide
     * @return void
     */
    public function testDeleteOk(): void
    {
        try {
            $this->passwordResetTokenDAO->delete(4);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail("Exception thrown: " . $e->getMessage());
        }
    }

    /**
     * @brief Test de la méthode delete avec un paramètre invalide
     * @return void
     */
    public function testDeleteThrowErrorWhenIdIsNull(): void
    {
        $this->expectException(TypeError::class);
        $this->passwordResetTokenDAO->delete(null);
    }

    /**
     * @brief Test de la méthode findByUserId avec un paramètre valide, mais un ID inexistant en base de données
     * @return void
     */
    public function testInsertOk(): void
    {
        try {
            $this->passwordResetTokenDAO->insert(new PasswordResetToken(4, 'tokenTest', new DateTime('2020-01-01 00:00:00')));
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail("Exception thrown: " . $e->getMessage());
        }
    }

    /**
     * @brief Test de la méthode insert avec un token null
     * @return void
     */
    public function testInsertThrowErrorWhenTokenIsNull(): void
    {
        $this->expectException(TypeError::class);
        $this->passwordResetTokenDAO->insert(new PasswordResetToken(4, null, new DateTime('2020-01-01 00:00:00')));
    }

    /**
     * @brief Test de la méthode insert avec une date de création null
     * @return void
     */
    public function testInsertThrowErrorWhenUserIdIsNull(): void
    {
        $this->expectException(TypeError::class);
        $this->passwordResetTokenDAO->insert(new PasswordResetToken(null, 'tokenTest', new DateTime('2020-01-01 00:00:00')));
    }

    /**
     * @brief Test de la méthode insert avec un token déjà existant pour un utilisateur
     * @return void
     */
    public function testInsertThrowErrorWhenTokenAlreadyExists(): void
    {
        $this->expectException(PDOException::class);
        $this->passwordResetTokenDAO->insert(new PasswordResetToken(1, 'reset_token_1', new DateTime('2020-01-01 00:00:00')));
    }

    /**
     * @brief Test de la méthode setPdo avec un paramètre valide
     * @return void
     */
    public function testSetPdoOk(): void
    {
        $this->passwordResetTokenDAO->setPdo(Db::getInstance()->getConnection());


        // Vérifie que la connexion n'est pas nulle
        $this->assertNotNull($this->passwordResetTokenDAO->getPdo());

        // Vérifie que la connexion est bien celle attendue
        $this->assertEquals(Db::getInstance()->getConnection(), $this->passwordResetTokenDAO->getPdo());

        // Vérifie que la connexion est bien une instance de PDO
        $this->assertInstanceOf(PDO::class, $this->passwordResetTokenDAO->getPdo());
    }

    /**
     * @brief Test de la méthode findByToken avec un paramètre valide
     * @return void
     * @throws DateMalformedStringException Exception levée si la date est mal formée
     */
    public function testFindByTokenOk(): void
    {
        $this->assertEquals(1, $this->passwordResetTokenDAO->findByToken('reset_token_1')->getUserId());
    }

    /**
     * @brief Test de la méthode findByToken avec un token null
     * @return void
     * @throws DateMalformedStringException Exception levée si la date est mal formée
     */
    public function testFindByTokenThrowErrorWhenTokenIsNull(): void
    {
        $this->expectException(TypeError::class);
        $this->passwordResetTokenDAO->findByToken(null);
    }

    /**
     * @brief Test de la méthode findByToken avec un token inexistant
     * @return void
     * @throws DateMalformedStringException Exception levée si la date est mal formée
     */
    public function testFindByTokenThrowErrorWhenTokenDoesNotExist(): void
    {
        $this->assertNull($this->passwordResetTokenDAO->findByToken('tokenDoesNotExist'));
    }

    /**
     * @brief Test de la méthode getPdo qui retourne une connexion valide
     * @return void
     */
    public function testGetPdo(): void
    {
        $this->assertNotNull($this->passwordResetTokenDAO->getPdo());
        $this->assertInstanceOf(PDO::class, $this->passwordResetTokenDAO->getPdo());
    }

    /**
     * @brief Initialisation de la classe PasswordResetTokenDAO
     * @return void
     */
    protected function setUp(): void
    {
        $this->passwordResetTokenDAO = new PasswordResetTokenDAO(Db::getInstance()->getConnection());
    }
}
