<?php
/**
 * @file UserDaoTest.php
 * @brief Le fichier contient la déclaration et la définition de la classe GameDao
 * @author Conchez-Boueytou Robin
 * @date 14/11/2024
 * @version 0.1
 */

require_once __DIR__ . '/../include.php';

use PHPUnit\Framework\TestCase;
/**
 * @brief Classe UserDAOTest
 * @details La classe UserDAOTest permet de tester les méthodes de la classe UserDAO
 */
class UserDAOTest extends TestCase
{
    /**
     * @brief UserDAO
     *
     * @var UserDAO
     */
    private UserDAO $userDAO;
    protected function setUp(): void
    {
        /**
         * @brief Instanciation d'un objet UserDAO pour les tests
         */
        $this->userDAO = new UserDAO(Db::getInstance()->getConnection());
    }
    /**
     * @brief Test de la méthode getUserDAO
     * @return void
     */
    public function testGetUserDAO(): void
    {
        $this->assertInstanceOf(UserDAO::class, $this->userDAO);
    }
    /**
     * @brief Test de la méthode setUserDAO
     * @return void
     */
    public function testSetUserDAO(): void
    {
        $this->userDAO = new UserDAO(Db::getInstance()->getConnection());
        $this->assertInstanceOf(UserDAO::class, $this->userDAO);
    }

    /**
     * @brief Test de la méthode getPdo
     * @return void
     */
    public function testGetPdo(): void
    {
        $this->assertInstanceOf(PDO::class, $this->userDAO->getPdo());
    }
    /**
     * @brief Test de la méthode setPdo
     * @return void
     */
    public function testSetPdo(): void
    {
        $this->userDAO->setPdo(Db::getInstance()->getConnection());
        $this->assertInstanceOf(PDO::class, $this->userDAO->getPdo());
    }

    /**
     * @brief Test de la méthode findById
     * @return void
     */
    public function testFindById(): void
    {
        $this->assertEquals('john.doe@example.com', $this->userDAO->findById(1)->getEmail());
    }

    /**
     * @brief Test de la méthode findByEmail
     * @return void
     * @throws DateMalformedStringException
     */
    public function testFindByEmail(): void
    {
        $this->assertEquals(1, $this->userDAO->findByEmail('john.doe@example.com')->getId());
    }

    /**
     * @brief Test de la méthode hydrate
     * @return void
     * @throws DateMalformedStringException
     */
    public function testHydrate(): void
    {
        $userTab = [
            'id' => 1,
            'email' => 'john.doe@example.com',
            'emailVerifiedAt' => new DateTime('2024-11-06'),
            'emailVerifyToken' => 'token123',
            'password' => 'password_hash1',
            'disabled' => 0,
            'created_at' => '2024-11-06',
            'updated_at' => '2024-11-06'];
        $user = $this->userDAO->hydrate($userTab);
        $this->assertEquals(1, $user->getId());
    }
}
