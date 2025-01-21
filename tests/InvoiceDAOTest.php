<?php
/**
 * @file InvoiceDAOTest.php
 * @brief Le fichier contient la déclaration et la définition de la classe InvoiceDAOTest
 * @author DESESSARD Estéban
 * @date 03/12/2024
 * @version 0.1
 */

require_once __DIR__ . '/../include.php';


use ComusParty\App\Db;
use ComusParty\Models\InvoiceDAO;
use ComusParty\Models\PaymentType;
use PHPUnit\Framework\TestCase;

/**
 * @brief Classe InvoiceDAOTest
 * @details La classe InvoiceDAOTest permet de tester les méthodes de la classe InvoiceDAO
 */
class InvoiceDAOTest extends TestCase
{
    /**
     * @brief InvoiceDAO
     * @var InvoiceDAO
     */
    private InvoiceDAO $invoiceDAO;

    /**
     * @brief Test de la méthode findById avec un paramètre valide
     * @throws DateMalformedStringException si la date est mal formée
     */
    public function testFindByIdWithValidId(): void
    {
        $this->assertEquals(1, $this->invoiceDAO->findById(1)->getId());
    }

    /**
     * @brief Test de la méthode findById avec un paramètre valide mais un ID inexistant en base de données
     * @throws DateMalformedStringException si la date est mal formée
     */
    public function testFindByIdWithValidButInexistantId(): void
    {
        $this->assertNull($this->invoiceDAO->findById(0));
    }

    /**
     * @brief Test de la méthode findById avec un paramètre invalide
     * @throws DateMalformedStringException si la date est mal formée
     */
    public function testFindByIdWithInvalidIdThrowTypeError(): void
    {
        $this->expectException(TypeError::class);
        $this->invoiceDAO->findById("un");
    }

    /**
     * @brief Test de la méthode findById avec un paramètre null
     * @throws DateMalformedStringException si la date est mal formée
     */
    public function testFindByIdWithNullId(): void
    {
        $this->assertNull($this->invoiceDAO->findById(null));
    }

    /**
     * @brief Test de la méthode hydrate avec un paramètre valide
     * @throws DateMalformedStringException si la date est mal formée
     */
    public function testHydrateWithValidData(): void
    {
        $data = [
            'id' => 1,
            'player_uuid' => 'uuid1',
            'payment_type' => 'card',
            'created_at' => '2024-01-01'
        ];
        $invoiceWithHydrate = $this->invoiceDAO->hydrate($data);

        $this->assertEquals(1, $invoiceWithHydrate->getId());
        $this->assertEquals('uuid1', $invoiceWithHydrate->getPlayerUuid());
        $this->assertEquals(PaymentType::Card, $invoiceWithHydrate->getPaymentType());
        $this->assertEquals(new DateTime('2024-01-01'), $invoiceWithHydrate->getCreatedAt());
    }

    /**
     * @brief Test de la méthode hydrate avec un paramètre invalide
     * @throws DateMalformedStringException si la date est mal formée
     */
    public function testHydrateWithInvalidDataThrowTypeError(): void
    {
        $this->expectException(TypeError::class);
        $this->invoiceDAO->hydrate('data');
    }

    /**
     * @brief Test de la méthode hydrate avec un paramètre null
     * @throws DateMalformedStringException si la date est mal formée
     */
    public function testHydrateWithNullDataThrowTypeError(): void
    {
        $this->expectException(TypeError::class);
        $this->invoiceDAO->hydrate(null);
    }

    /**
     * @brief Test de la méthode hydrate avec un paramètre valide mais le tableau associatif ne contient pas toutes les clés
     * @throws DateMalformedStringException si la date est mal formée
     */
    public function testHydrateWithValidDataButNotAllKeys(): void
    {
        $data = [
            'id' => 1,
            'player_uuid' => 'uuid1',
            'payment_type' => 'card',
            'created_at' => '2024-01-01'
        ];
        $invoiceWithHydrate = $this->invoiceDAO->hydrate($data);

        $this->assertEquals(1, $invoiceWithHydrate->getId());
        $this->assertEquals('uuid1', $invoiceWithHydrate->getPlayerUuid());
        $this->assertEquals(PaymentType::Card, $invoiceWithHydrate->getPaymentType());
        $this->assertEquals(new DateTime('2024-01-01'), $invoiceWithHydrate->getCreatedAt());
    }

    /**
     * @brief Instanciation d'un objet InvoiceDAO pour les tests
     * @return void
     */
    protected function setUp(): void
    {
        $this->invoiceDAO = new InvoiceDAO(Db::getInstance()->getConnection());
    }
}
