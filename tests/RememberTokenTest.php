<?php
/**
 * @brief Classe RememberTokenTest
 *
 * @file RememberTokenTest.php
 * @author Lucas ESPIET "espiet.l@valbion.com"
 * @version 1.0
 * @date 2025-02-26
 */


use ComusParty\Models\RememberToken;
use PHPUnit\Framework\TestCase;

/**
 * @brief Classe RememberTokenTest
 * @details La classe RememberTokenTest permet de tester les méthodes de la classe RememberToken
 */
class RememberTokenTest extends TestCase
{
    private RememberToken $rememberToken;

    /**
     * @brief Test de la méthode __construct avec des données valides
     * @return void
     */
    public function testConstructWithValidDatas(): void
    {
        $this->rememberToken = new RememberToken(
            1,
            "abcd",
            new DateTime("2025-02-26 00:00:00"),
            new DateTime("2025-02-26 00:00:00")
        );

        $this->assertNotNull($this->rememberToken);
    }

    /**
     * @brief Test de la méthode __construct avec un objet invalide
     * @return void
     */
    public function testConstructWithInvalidObjectThrowUnhandledMatchError(): void
    {
        $this->expectException(ArgumentCountError::class);
        $this->rememberToken = new RememberToken();
    }
}
