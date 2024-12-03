<?php
/**
 * @file InvoiceTest.php
 * @brief Le fichier contient la déclaration et la définition de la classe InvoiceTest
 * @author DESESSARD Estéban
 * @date 03/12/2024
 * @version 0.1
 */

require_once __DIR__ . '/../include.php';

use ComusParty\Models\Article;
use ComusParty\Models\Invoice;
use ComusParty\Models\paymentType;
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
            paymentType: paymentType::Card,
            createdAt: new DateTime('2024-01-01'),
            articles: []
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
        $this->assertEquals(paymentType::Card, $this->invoice->getPaymentType());
    }

    /**
     * @brief Test de la méthode setPaymentType avec un paramètre valide
     * @return void
     */
    public function testSetPaymentTypeWithValidPaymentType(): void
    {
        $this->invoice->setPaymentType(paymentType::PayPal);
        $this->assertEquals(paymentType::PayPal, $this->invoice->getPaymentType());
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

    /**
     * @brief Test de la méthode getArticles
     * @return void
     */
    public function testGetArticles(): void
    {
        $this->assertEquals([], $this->invoice->getArticles());
    }

    /**
     * @brief Test de la méthode setArticles avec un paramètre valide
     * @return void
     */
    public function testSetArticlesWithValidArticles(): void
    {
        $this->invoice->setArticles([new Article()]);
        $this->assertEquals([new Article()], $this->invoice->getArticles());
    }

    /**
     * @brief Test de la méthode setArticles avec un paramètre null
     * @return void
     */
    public function testSetArticlesWithNullArticles(): void
    {
        $this->invoice->setArticles(null);
        $this->assertEquals(null, $this->invoice->getArticles());
    }

    /**
     * @brief Test de la méthode setArticles avec un paramètre invalide
     * @return void
     */
    public function testSetArticlesWithInvalidArticlesThrowTypeError(): void
    {
        $this->expectException(TypeError::class);
        $this->invoice->setArticles('articles');
    }

    /**
     * @brief Test de la méthode addArticle avec un paramètre valide
     * @return void
     */
    public function testAddArticleWithValidArticle(): void
    {
        $this->invoice->addArticle(new Article());
        $this->assertEquals([new Article()], $this->invoice->getArticles());
    }

    /**
     * @brief Test de la méthode addArticle avec un paramètre invalide
     * @return void
     */
    public function testAddArticleWithInvalidArticleThrowTypeError(): void
    {
        $this->expectException(TypeError::class);
        $this->invoice->addArticle('article');
    }

    /**
     * @brief Test de la méthode addArticle avec un paramètre null
     * @return void
     */
    public function testAddArticleWithNullArticleThrowTypeError(): void
    {
        $this->expectException(TypeError::class);
        $this->invoice->addArticle(null);
    }

    /**
     * @brief Test de la méthode addArticle avec un plusieurs fois le même article
     * @return void
     */
    public function testAddArticleWithSameArticleAddOnlyOneArticle(): void
    {
        $article = new Article();
        $this->invoice->addArticle($article);
        $this->invoice->addArticle($article);
        $this->assertEquals([new Article()], $this->invoice->getArticles());
    }

    /**
     * @brief Test de la méthode removeArticle avec un paramètre valide
     * @return void
     */
    public function testRemoveArticleWithValidArticle(): void
    {
        $article = new Article();
        $this->invoice->addArticle($article);
        $this->invoice->removeArticle($article);
        $this->assertEquals([], $this->invoice->getArticles());
    }

    /**
     * @brief Test de la méthode removeArticle avec un paramètre invalide
     * @return void
     */
    public function testRemoveArticleWithInvalidArticleThrowTypeError(): void
    {
        $this->expectException(TypeError::class);
        $this->invoice->removeArticle('article');
    }

    /**
     * @brief Test de la méthode removeArticle avec un paramètre null
     * @return void
     */
    public function testRemoveArticleWithNullArticleThrowTypeError(): void
    {
        $this->expectException(TypeError::class);
        $this->invoice->removeArticle(null);
    }

    /**
     * @brief Test de la méthode removeArticle avec un paramètre non présent dans la liste
     * @return void
     */
    public function testRemoveArticleWithArticleNotInListIgnoreRemoveRequest(): void
    {
        $this->invoice->removeArticle(new Article());
        $this->assertEquals([], $this->invoice->getArticles());
    }
}
