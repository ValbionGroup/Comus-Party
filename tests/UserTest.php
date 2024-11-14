<?php
/**
 * @file UserTest.php
 * @brief Le fichier contient la déclaration et la définition de la classe GameDao
 * @author Conchez-Boueytou Robin
 * @date 14/11/2024
 * @version 0.1
 */
require_once __DIR__ . '/../include.php';

use PHPUnit\Framework\TestCase;
/**
 * @brief Classe UserTest
 * @details La classe UserTest permet de tester les méthodes de la classe User
 */
class UserTest extends TestCase
{
    /**
     * @brief User
     *
     * @var User
     */
    private User $user;
    protected function setUp(): void
    {
        /**
         * @brief Instanciation d'un objet User pour les tests
         */
        $this->user = new User(
            id: 1,
            email: 'user1@example.com',
            emailVerifiedAt: new DateTime('2024-11-06'),
            emailVerifyToken: 'token123',
            password: 'password_hash1',
            disabled: 0,
            created_at: new DateTime('2024-11-06'),
            updated_at: new DateTime('2024-11-06')
        );
    }
    /**
     * @brief Test de la méthode getUser
     * @return void
     */
    public function testGetUser(): void
    {
        $this->assertInstanceOf(User::class, $this->user);
    }
    /**
     * @brief Test de la méthode setUser
     * @return void
     */
    public function testSetUser(): void
    {
        $this->user = new User();
        $this->assertInstanceOf(User::class, $this->user);
    }

    /**
     * @brief Test de la méthode getId
     * @return void
     */
    public function testGetId(): void
    {
        $this->assertEquals(1, $this->user->getId());
    }

    /**
     * @brief Test de la méthode setId
     * @return void
     */
    public function testSetId(): void
    {
        $this->user->setId(2);
        $this->assertEquals(2, $this->user->getId());
    }

    /**
     * @brief Test de la méthode getEmail
     * @return void
     */
    public function testGetEmail(): void
    {
        $this->assertEquals('user1@example.com', $this->user->getEmail());
    }

    /**
     * @brief Test de la méthode setEmail
     * @return void
     */
    public function testSetEmail(): void
    {
        $this->user->setEmail('user2@example.com');
        $this->assertEquals('user2@example.com', $this->user->getEmail());
    }

    /**
     * @brief Test de la méthode getEmailVerifiedAt
     * @return void
     */
    public function testGetEmailVerifiedAt(): void
    {
        $this->assertEquals(new DateTime('2024-11-06'), $this->user->getEmailVerifiedAt());
    }

    /**
     * @brief Test de la méthode setEmailVerifiedAt
     * @return void
     */
    public function testSetEmailVerifiedAt(): void
    {
        $this->user->setEmailVerifiedAt(new DateTime('2024-11-07'));
        $this->assertEquals(new DateTime('2024-11-07'), $this->user->getEmailVerifiedAt());
    }

    /**
     * @brief Test de la méthode getEmailVerifyToken
     * @return void
     */
    public function testGetEmailVerifyToken(): void
    {
        $this->assertEquals('token123', $this->user->getEmailVerifyToken());
    }

    /**
     * @brief Test de la méthode setEmailVerifyToken
     * @return void
     */
    public function testSetEmailVerifyToken(): void
    {
        $this->user->setEmailVerifyToken('token456');
        $this->assertEquals('token456', $this->user->getEmailVerifyToken());
    }

    /**
     * @brief Test de la méthode getPassword
     * @return void
     */
    public function testGetPassword(): void
    {
        $this->assertEquals('password_hash1', $this->user->getPassword());
    }

    /**
     * @brief Test de la méthode setPassword
     * @return void
     */
    public function testSetPassword(): void
    {
        $this->user->setPassword('password_hash2');
        $this->assertEquals('password_hash2', $this->user->getPassword());
    }

    /**
     * @brief Test de la méthode getDisabled
     * @return void
     */
    public function testGetDisabled(): void
    {
        $this->assertEquals(0, $this->user->getDisabled());
    }

    /**
     * @brief Test de la méthode setDisabled
     * @return void
     */
    public function testSetDisabled(): void
    {
        $this->user->setDisabled(1);
        $this->assertEquals(1, $this->user->getDisabled());
    }

    /**
     * @brief Test de la méthode getCreatedAt
     * @return void
     */
    public function testGetCreatedAt(): void
    {
        $this->assertEquals(new DateTime('2024-11-06'), $this->user->getCreatedAt());
    }

    /**
     * @brief Test de la méthode setCreatedAt
     * @return void
     */
    public function testSetCreatedAt(): void
    {
        $this->user->setCreatedAt(new DateTime('2024-11-07'));
        $this->assertEquals(new DateTime('2024-11-07'), $this->user->getCreatedAt());
    }

    /**
     * @brief Test de la méthode getUpdatedAt
     * @return void
     */
    public function testGetUpdatedAt(): void
    {
        $this->assertEquals(new DateTime('2024-11-06'), $this->user->getUpdatedAt());
    }

    /**
     * @brief Test de la méthode setUpdatedAt
     * @return void
     */
    public function testSetUpdatedAt(): void
    {
        $this->user->setUpdatedAt(new DateTime('2024-11-07'));
        $this->assertEquals(new DateTime('2024-11-07'), $this->user->getUpdatedAt());
    }
}
