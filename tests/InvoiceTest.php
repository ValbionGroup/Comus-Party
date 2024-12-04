<?php
/**
 * @file InvoiceTest.php
 * @brief Le fichier contient la déclaration et la définition de la classe InvoiceTest
 * @author DESESSARD Estéban
 * @date 03/12/2024
 * @version 0.1
 */

require_once __DIR__ . '/../include.php';

use ComusParty\Models\Invoice;
use ComusParty\Models\PaymentType;
use PHPUnit\Framework\TestCase;

/**
 * @brief Classe InvoiceTest
 * @details La classe InvoiceTest permet de tester les méthodes de la classe Invoice
 */
class InvoiceTest extends TestCase
{
    /**
     * @brief Invoice
     * @var Invoice
     */
    private Invoice $invoice;

    /**
     * @brief Instanciation d'un objet Invoice pour les tests
     * @return void
     */
    protected function setUp(): void
    {
        $this->invoice = new Invoice(
            id: 1,
            playerUuid: 'uuid1',
            paymentType: PaymentType::Card,
            createdAt: new DateTime('2024-01-01')
        );
    }

    /**
     * @brief Test de la méthode getId
     * @return void
     */
    public function testGetId(): void
    {
        $this->assertEquals(1, $this->invoice->getId());
    }

    /**
     * @brief Test de la méthode setId avec un paramètre valide
     * @return void
     */
    public function testSetIdWithValidId(): void
    {
        $this->invoice->setId(2);
        $this->assertEquals(2, $this->invoice->getId());
    }

    /**
     * @brief Test de la méthode setId avec un paramètre invalide
     * @return void
     */
    public function testSetIdWithInvalidIdThrowTypeError(): void
    {
        $this->expectException(TypeError::class);
        $this->invoice->setId('deux');
    }

    /**
     * @brief Test de la méthode getPlayerUuid
     * @return void
     */
    public function testGetPlayerUuid(): void
    {
        $this->assertEquals('uuid1', $this->invoice->getPlayerUuid());
    }

    /**
     * @brief Test de la méthode setPlayerUuid avec un paramètre valide
     * @return void
     */
    public function testSetPlayerUuidWithValidUuid(): void
    {
        $this->invoice->setPlayerUuid('uuid2');
        $this->assertEquals('uuid2', $this->invoice->getPlayerUuid());
    }

    /**
     * @brief Test de la méthode getPaymentType
     * @return void
     */
    public function testGetPaymentType(): void
    {
        $this->assertEquals(PaymentType::Card, $this->invoice->getPaymentType());
    }

    /**
     * @brief Test de la méthode setPaymentType avec un paramètre valide
     * @return void
     */
    public function testSetPaymentTypeWithValidPaymentType(): void
    {
        $this->invoice->setPaymentType(PaymentType::PayPal);
        $this->assertEquals(PaymentType::PayPal, $this->invoice->getPaymentType());
    }

    /**
     * @brief Test de la méthode setPaymentType avec un paramètre invalide
     * @return void
     */
    public function testSetPaymentTypeWithInvalidPaymentTypeThrowTypeError(): void
    {
        $this->expectException(TypeError::class);
        $this->invoice->setPaymentType('ComusCoins');
    }

    /**
     * @brief Test de la méthode getCreatedAt
     * @return void
     */
    public function testGetCreatedAt(): void
    {
        $this->assertEquals(new DateTime('2024-01-01'), $this->invoice->getCreatedAt());
    }

    /**
     * @brief Test de la méthode setCreatedAt avec un paramètre valide
     * @return void
     */
    public function testSetCreatedAtWithValidCreatedAt(): void
    {
        $this->invoice->setCreatedAt(new DateTime('2024-01-02'));
        $this->assertEquals(new DateTime('2024-01-02'), $this->invoice->getCreatedAt());
    }

    /**
     * @brief Test de la méthode setCreatedAt avec un paramètre invalide
     * @return void
     */
    public function testSetCreatedAtWithInvalidCreatedAtThrowTypeError(): void
    {
        $this->expectException(TypeError::class);
        $this->invoice->setCreatedAt('2024-01-02');
    }
}
