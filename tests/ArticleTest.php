<?php
/**
 * @file ArticleTest.php
 * @brief Le fichier contient la déclaration et la définition de la classe ArticleTest
 * @author DESESSARD Estéban
 * @date 20/11/2024
 * @version 0.1
 */

use ComusParty\Models\Article;
use ComusParty\Models\ArticleType;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../include.php';

/**
 * @brief Classe ArticleTest
 * @details La classe ArticleTest permet de tester les méthodes de la classe Article
 */
class ArticleTest extends TestCase
{
    /**
     * @brief Article
     * @var Article
     */
    private Article $article;

    /**
     * @brief Instanciation d'un objet Article pour les tests
     * @return void
     */
    protected function setUp(): void
    {
        $this->article = new Article(
            id: 1,
            createdAt: new DateTime('2024-01-01'),
            updatedAt: new DateTime('2024-11-14'),
            name: 'TestTitle',
            type: ArticleType::ProfilePicture,
            description: 'TestContent',
            pricePoint: 500,
            priceEuro: 5.0,
            pathImg: 'test.jpg'
        );
    }

    /**
     * @brief Test de la méthode getId
     * @return void
     */
    public function testGetId(): void
    {
        $this->assertEquals(1, $this->article->getId());
    }

    /**
     * @brief Test de la méthode setId avec un paramètre valide
     * @return void
     */
    public function testSetIdWithValidId(): void
    {
        $this->article->setId(2);
        $this->assertEquals(2, $this->article->getId());
    }

    /**
     * @brief Test de la méthode setId avec un paramètre invalide
     * @return void
     */
    public function testSetIdWithInvalidId(): void
    {
        $this->expectException(TypeError::class);
        $this->article->setId('caca-huète');
    }

    /**
     * @brief Test de la méthode setId avec un paramètre null
     * @return void
     */
    public function testSetIdWithNullId(): void
    {
        $this->article->setId(null);
        $this->assertEquals(null, $this->article->getId());
    }


    /**
     * @brief Test de la méthode getName
     * @return void
     */
    public function testGetName(): void
    {
        $this->assertEquals('TestTitle', $this->article->getName());
    }

    /**
     * @brief Test de la méthode setName avec un paramètre valide
     * @return void
     */
    public function testSetNameWithValidName(): void
    {
        $this->article->setName('TestTitle2');
        $this->assertEquals('TestTitle2', $this->article->getName());
    }

    /**
     * @brief Test de la méthode setName avec un paramètre null
     * @return void
     */
    public function testSetNameWithNullName(): void
    {
        $this->article->setName(null);
        $this->assertEquals(null, $this->article->getName());
    }


    /**
     * @brief Test de la méthode getType
     * @return void
     */
    public function testGetType(): void
    {
        $this->assertEquals(ArticleType::ProfilePicture, $this->article->getType());
    }

    /**
     * @brief Test de la méthode setType avec un paramètre valide
     * @return void
     */
    public function testSetTypeWithValidType(): void
    {
        $this->article->setType(ArticleType::Banner);
        $this->assertEquals(ArticleType::Banner, $this->article->getType());
    }

    /**
     * @brief Test de la méthode setType avec un paramètre invalide
     * @return void
     */
    public function testSetTypeWithInvalidType(): void
    {
        $this->expectException(TypeError::class);
        $this->article->setType('Banner');
    }


    /**
     * @brief Test de la méthode getDescription
     * @return void
     */
    public function testGetDescription(): void
    {
        $this->assertEquals('TestContent', $this->article->getDescription());
    }

    /**
     * @brief Test de la méthode setDescription avec un paramètre valide
     * @return void
     */
    public function testSetDescriptionWithValidDescription(): void
    {
        $this->article->setDescription('TestContent2');
        $this->assertEquals('TestContent2', $this->article->getDescription());
    }

    /**
     * @brief Test de la méthode setDescription avec un paramètre null
     * @return void
     */
    public function testSetDescriptionWithNullDescription(): void
    {
        $this->article->setDescription(null);
        $this->assertEquals(null, $this->article->getDescription());
    }


    /**
     * @brief Test de la méthode getPricePoint
     * @return void
     */
    public function testGetPricePoint(): void
    {
        $this->assertEquals(500, $this->article->getPricePoint());
    }

    /**
     * @brief Test de la méthode setPricePoint avec un paramètre valide
     * @return void
     */
    public function testSetPricePointWithValidPricePoint(): void
    {
        $this->article->setPricePoint(600);
        $this->assertEquals(600, $this->article->getPricePoint());
    }

    /**
     * @brief Test de la méthode setPricePoint avec un paramètre invalide
     * @return void
     */
    public function testSetPricePointWithInvalidPricePoint(): void
    {
        $this->expectException(TypeError::class);
        $this->article->setPricePoint('caca-huète');
    }

    /**
     * @brief Test de la méthode setPricePoint avec un paramètre null
     * @return void
     */
    public function testSetPricePointWithNullPricePoint(): void
    {
        $this->article->setPricePoint(null);
        $this->assertEquals(null, $this->article->getPricePoint());
    }


    /**
     * @brief Test de la méthode getPriceEuro
     * @return void
     */
    public function testGetPriceEuro(): void
    {
        $this->assertEquals(5.0, $this->article->getPriceEuro());
    }

    /**
     * @brief Test de la méthode setPriceEuro avec un paramètre valide
     * @return void
     */
    public function testSetPriceEuroWithValidPriceEuro(): void
    {
        $this->article->setPriceEuro(6.0);
        $this->assertEquals(6.0, $this->article->getPriceEuro());
    }

    /**
     * @brief Test de la méthode setPriceEuro avec un paramètre invalide
     * @return void
     */
    public function testSetPriceEuroWithInvalidPriceEuro(): void
    {
        $this->expectException(TypeError::class);
        $this->article->setPriceEuro('caca-huète');
    }

    /**
     * @brief Test de la méthode setPriceEuro avec un paramètre null
     * @return void
     */
    public function testSetPriceEuroWithNullPriceEuro(): void
    {
        $this->article->setPriceEuro(null);
        $this->assertEquals(null, $this->article->getPriceEuro());
    }


    /**
     * @brief Test de la méthode getPathImg
     * @return void
     */
    public function testGetPathImg(): void
    {
        $this->assertEquals('test.jpg', $this->article->getPathImg());
    }

    /**
     * @brief Test de la méthode setPathImg avec un paramètre valide
     * @return void
     */
    public function testSetPathImgWithValidPathImg(): void
    {
        $this->article->setPathImg('test2.jpg');
        $this->assertEquals('test2.jpg', $this->article->getPathImg());
    }

    /**
     * @brief Test de la méthode setPathImg avec un paramètre null
     * @return void
     */
    public function testSetPathImgWithNullPathImg(): void
    {
        $this->article->setPathImg(null);
        $this->assertEquals(null, $this->article->getPathImg());
    }

    /**
     * @brief Test de la méthode getCreatedAt
     * @return void
     */
    public function testGetCreatedAt(): void
    {
        $this->assertEquals(new DateTime('2024-01-01'), $this->article->getCreatedAt());
    }

    /**
     * @brief Test de la méthode setCreatedAt avec un paramètre valide
     * @return void
     */
    public function testSetCreatedAtWithValidCreatedAt(): void
    {
        $this->article->setCreatedAt(new DateTime('2024-01-02'));
        $this->assertEquals(new DateTime('2024-01-02'), $this->article->getCreatedAt());
    }

    /**
     * @brief Test de la méthode setCreatedAt avec un paramètre invalide
     * @return void
     */
    public function testSetCreatedAtWithInvalidCreatedAt(): void
    {
        $this->expectException(TypeError::class);
        $this->article->setCreatedAt('2024-01-02');
    }

    /**
     * @brief Test de la méthode setCreatedAt avec un paramètre null
     * @return void
     */
    public function testSetCreatedAtWithNullCreatedAt(): void
    {
        $this->article->setCreatedAt(null);
        $this->assertEquals(null, $this->article->getCreatedAt());
    }


    /**
     * @brief Test de la méthode getUpdatedAt
     * @return void
     */
    public function testGetUpdatedAt(): void
    {
        $this->assertEquals(new DateTime('2024-11-14'), $this->article->getUpdatedAt());
    }

    /**
     * @brief Test de la méthode setUpdatedAt avec un paramètre valide
     * @return void
     */
    public function testSetUpdatedAtWithValidUpdatedAt(): void
    {
        $this->article->setUpdatedAt(new DateTime('2024-11-15'));
        $this->assertEquals(new DateTime('2024-11-15'), $this->article->getUpdatedAt());
    }

    /**
     * @brief Test de la méthode setUpdatedAt avec un paramètre invalide
     * @return void
     */
    public function testSetUpdatedAtWithInvalidUpdatedAt(): void
    {
        $this->expectException(TypeError::class);
        $this->article->setUpdatedAt('2024-11-15');
    }

    /**
     * @brief Test de la méthode setUpdatedAt avec un paramètre null
     * @return void
     */
    public function testSetUpdatedAtWithNullUpdatedAt(): void
    {
        $this->article->setUpdatedAt(null);
        $this->assertEquals(null, $this->article->getUpdatedAt());
    }
}
