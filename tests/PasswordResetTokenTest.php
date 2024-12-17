<?php
/**
 * @file PasswordResetTokenTest.php
 * @brief Le fichier contient la déclaration et la définition de la classe PasswordResetTokenTest
 * @author Lucas ESPIET
 * @date 2024-12-17
 * @version 1.0
 */

require_once __DIR__ . '/../include.php';

use ComusParty\Models\PasswordResetToken;
use PHPUnit\Framework\TestCase;

/**
 * @brief Classe InvoiceTest
 * @details La classe InvoiceTest permet de tester les méthodes de la classe Invoice
 */
class PasswordResetTokenTest extends TestCase
{
    /**
     * @brief Classe PasswordResetToken
     * @var PasswordResetToken
     */
    private PasswordResetToken $passwordResetToken;

    /**
     * @brief Test de la méthode getUserId qui retourne une valeur valide
     * @return void
     */
    public function testGetUserIdOk(): void
    {
        $this->assertEquals(1, $this->passwordResetToken->getUserId());
    }

    /**
     * @brief Test de la méthode getToken qui retourne une valeur valide
     * @return void
     */
    public function testGetTokenOk(): void
    {
        $this->assertEquals('token', $this->passwordResetToken->getToken());
    }

    /**
     * @brief Test de la méthode getCreatedAt qui retourne une valeur valide
     * @return void
     */
    public function testGetCreatedAtOk(): void
    {
        $this->assertEquals(new DateTime('2020-01-01 00:00:00'), $this->passwordResetToken->getCreatedAt());
    }

    /**
     * @brief Test de la méthode setUserId avec un paramètre valide
     * @return void
     */
    public function testSetUserIdOk(): void
    {
        $this->passwordResetToken->setUserId(2);
        $this->assertEquals(2, $this->passwordResetToken->getUserId());
    }

    /**
     * @brief Test de la méthode setToken avec un paramètre valide
     * @return void
     */
    public function testSetTokenOk(): void
    {
        $this->passwordResetToken->setToken('newToken');
        $this->assertEquals('newToken', $this->passwordResetToken->getToken());
    }

    /**
     * @brief Test de la méthode setCreatedAt avec un paramètre valide
     * @return void
     */
    public function testSetCreatedAtOk(): void
    {
        $this->passwordResetToken->setCreatedAt(new DateTime('2021-01-01 00:00:00'));
        $this->assertEquals(new DateTime('2021-01-01 00:00:00'), $this->passwordResetToken->getCreatedAt());
    }

    /**
     * @brief Initialisation des attributs
     * @return void
     */
    protected function setUp(): void
    {
        $this->passwordResetToken = new PasswordResetToken(
            1,
            'token',
            new DateTime('2020-01-01 00:00:00')
        );
    }
}