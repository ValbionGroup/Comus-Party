<?php
/**
 * @file ArticleDAOTest.php
 * @brief Le fichier contient la déclaration et la définition de la classe ArticleDAOTest
 * @author DESESSARD Estéban
 * @date 20/11/2024
 * @version 0.2
 */

use ComusParty\App\Db;
use ComusParty\Models\Article;
use ComusParty\Models\ArticleDAO;
use ComusParty\Models\ArticleType;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../include.php';

/**
 * @brief Classe ArticleDAOTest
 * @details La classe ArticleDAOTest permet de tester les méthodes de la classe ArticleDAO
 */
class ArticleDAOTest extends TestCase
{
    /**
     * @brief ArticleDAO
     * @var ArticleDAO
     */
    private ArticleDAO $articleDAO;

    /**
     * @brief Test de l'instanciation de la classe ArticleDAO génère une instance de la classe ArticleDAO
     * @return void
     */
    public function testGetArticleDAO(): void
    {
        $this->assertInstanceOf(ArticleDAO::class, $this->articleDAO);
    }

    /**
     * @brief Test de la méthode getPdo
     * @return void
     */
    public function testGetPdo(): void
    {
        $this->assertInstanceOf(PDO::class, $this->articleDAO->getPdo());
    }

    /**
     * @brief Test de la méthode setPdo avec un objet PDO
     * @return void
     */
    public function testSetPdoWithValidPdo(): void
    {
        $this->articleDAO->setPdo(Db::getInstance()->getConnection());
        $this->assertInstanceOf(PDO::class, $this->articleDAO->getPdo());
    }

    /**
     * @brief Test de la méthode setPdo avec un paramètre invalide
     * @return void
     */
    public function testSetPdoInvalidWithInvalidPdo(): void
    {
        $this->expectException(TypeError::class);
        $this->articleDAO->setPdo("pdo");
    }

    /**
     * @brief Test de la méthode setPdo avec un paramètre null
     * @return void
     */
    public function testSetPdoWithNullPdo(): void
    {
        $this->articleDAO->setPdo(null);
        $this->assertNull($this->articleDAO->getPdo());
    }

    /**
     * @brief Test de la méthode hydrate avec un tableau contenant des paramètres valides
     * @return void
     * @throws DateMalformedStringException
     */
    public function testHydrateWithValidArray(): void
    {
        $article = $this->articleDAO->hydrate(['id' => 1, 'name' => 'Article 1', 'type' => 'banner']);
        $this->assertEquals('1', $article->getId());
        $this->assertEquals('Article 1', $article->getName());
        $this->assertEquals(ArticleType::Banner, $article->getType());
    }

    /**
     * @brief Test de la méthode hydrate avec un tableau contenant des paramètres de valeur invalide
     * @return void
     * @throws DateMalformedStringException
     */
    public function testHydrateWithInvalidArray(): void
    {
        $this->expectException(TypeError::class);
        $article = $this->articleDAO->hydrate(['id' => '1', 'name' => 'Article 1', 'type' => ArticleType::Banner]);
        $this->assertEquals('1', $article->getId());
        $this->assertEquals('Article 1', $article->getName());
        $this->assertEquals(ArticleType::Banner, $article->getType());
    }

    /**
     * @brief Test de la méthode hydrate avec un paramètre du mauvais type
     * @return void
     * @throws DateMalformedStringException
     */
    public function testHydrateWithInvalidType(): void
    {
        $this->expectException(TypeError::class);
        $article = $this->articleDAO->hydrate('id');
    }

    /**
     * @brief Test de la méthode hydrateMany avec un tableau contenant des paramètres invalides
     * @return void
     * @throws DateMalformedStringException
     */
    public function testHydrateManyWithValidArray(): void
    {
        $articles = $this->articleDAO->hydrateMany([
            ['id' => 1, 'name' => 'Article 1', 'type' => 'banner'],
            ['id' => 2, 'name' => 'Article 2', 'type' => 'pfp']]);
        $this->assertEquals('1', $articles[0]->getId());
        $this->assertEquals('Article 1', $articles[0]->getName());
        $this->assertEquals(ArticleType::Banner, $articles[0]->getType());

        $this->assertEquals('2', $articles[1]->getId());
        $this->assertEquals('Article 2', $articles[1]->getName());
        $this->assertEquals(ArticleType::ProfilePicture, $articles[1]->getType());
    }

    /**
     * @brief Test de la méthode hydrateMany avec un tableau contenant des paramètres invalides
     * @return void
     * @throws DateMalformedStringException
     */
    public function testHydrateManyWithInvalidArray(): void
    {
        $this->expectException(TypeError::class);
        $articles = $this->articleDAO->hydrateMany([
            ['id' => '1', 'name' => 'Article 1', 'type' => ArticleType::Banner],
            ['id' => '2', 'name' => 'Article 2', 'type' => ArticleType::ProfilePicture]]);
        $this->assertEquals('1', $articles[0]->getId());
        $this->assertEquals('Article 1', $articles[0]->getName());
        $this->assertEquals(ArticleType::Banner, $articles[0]->getType());

        $this->assertEquals('2', $articles[1]->getId());
        $this->assertEquals('Article 2', $articles[1]->getName());
        $this->assertEquals(ArticleType::ProfilePicture, $articles[1]->getType());
    }

    /**
     * @brief Test de la méthode hydrateMany avec un paramètre du mauvais type
     * @return void
     * @throws DateMalformedStringException
     */
    public function testHydrateManyWithInvalidType(): void
    {
        $this->expectException(TypeError::class);
        $articles = $this->articleDAO->hydrateMany('array');
    }

    /**
     * @brief Test de la méthode findById avec un ID valide et existant dans la base de données
     * @return void
     */
    public function testFindByIdWithValidId(): void
    {
        $this->assertInstanceOf(Article::class, $this->articleDAO->findById('1'));
    }

    /**
     * @brief Test de la méthode findById avec un ID valide mais non-existant dans la base de données
     * @return void
     */
    public function testFindByIdWithInvalidId(): void
    {
        $this->assertNull($this->articleDAO->findById('42'));
    }

    /**
     * @brief Test de la méthode findById avec un paramètre null
     * @return void
     */
    public function testFindByIdWithNullId(): void
    {
        $this->expectException(TypeError::class);
        $this->articleDAO->findById(null);
    }

    /**
     * @brief Test de la méthode findAll avec des articles dans la base de données
     * @return void
     * @throws DateMalformedStringException
     */
    public function testFindAllWithArticles(): void
    {
        $articles = $this->articleDAO->findAll();
        $this->assertIsArray($articles);
        $this->assertNotEmpty($articles);
        $this->assertInstanceOf(Article::class, $articles[0]);
    }

    /**
     * @brief Test de la méthode findAllPfps avec des articles de type pfp dans la base de données
     * @return void
     * @throws DateMalformedStringException
     */
    public function testFindAllPfpsWithPfps(): void
    {
        $pfps = $this->articleDAO->findAllPfps();
        $this->assertIsArray($pfps);
        $this->assertNotEmpty($pfps);
        $this->assertInstanceOf(Article::class, $pfps[0]);
        $this->assertEquals(ArticleType::ProfilePicture, $pfps[0]->getType());
    }

    /**
     * @brief Test de la méthode findAllBanners avec des articles de type banner dans la base de données
     * @return void
     * @throws DateMalformedStringException
     */
    public function testFindAllBannersWithBanners(): void
    {
        $banners = $this->articleDAO->findAllBanners();
        $this->assertIsArray($banners);
        $this->assertNotEmpty($banners);
        $this->assertInstanceOf(Article::class, $banners[0]);
        $this->assertEquals(ArticleType::Banner, $banners[0]->getType());
    }

    /**
     * @brief Test de la méthode findActivePfpByPlayerUuid avec un UUID valide et existant dans la base de données et ayant une photo de profil achetée et active
     * @return void
     * @throws DateMalformedStringException
     */
    public function testFindActivePfpByPlayerUuidWithValidUuidAndActivePfp(): void
    {
        $this->assertInstanceOf(Article::class, $this->articleDAO->findActivePfpByPlayerUuid('uuid1'));
    }

    /**
     * @brief Test de la méthode findActivePfpByPlayerUuid avec un UUID valide et existant dans la base de données mais n'ayant pas de photo de profil achetée et active
     * @return void
     * @throws DateMalformedStringException
     */
    public function testFindActivePfpByPlayerUuidWithValidUuidAndNoActivePfp(): void
    {
        $this->assertNull($this->articleDAO->findActivePfpByPlayerUuid('uuid3'));
    }

    /**
     * @brief Test de la méthode findActivePfpByPlayerUuid avec un UUID valide mais non-existant dans la base de données
     * @return void
     * @throws DateMalformedStringException
     */
    public function testFindActivePfpByPlayerUuidWithInvalidUuid(): void
    {
        $this->assertNull($this->articleDAO->findActivePfpByPlayerUuid('42uuid'));
    }

    /**
     * @brief Test de la méthode findActivePfpByPlayerUuid avec un paramètre null
     * @return void
     * @throws DateMalformedStringException
     */
    public function testFindActivePfpByPlayerUuidWithNullUuid(): void
    {
        $this->expectException(TypeError::class);
        $this->articleDAO->findActivePfpByPlayerUuid(null);
    }

    /**
     * @brief Test de la méthode findActiveBannerByPlayerUuid avec un UUID valide et existant dans la base de données et ayant une banière achetée et active
     * @return void
     * @throws DateMalformedStringException
     */
    public function testFindActiveBannerByPlayerUuidWithValidUuidAndActiveBanner(): void
    {
        $this->assertInstanceOf(Article::class, $this->articleDAO->findActiveBannerByPlayerUuid('uuid1'));
    }

    /**
     * @brief Test de la méthode findActiveBannerByPlayerUuid avec un UUID valide et existant dans la base de données mais n'ayant pas de bannière achetée et active
     * @return void
     * @throws DateMalformedStringException
     */
    public function testFindActiveBannerByPlayerUuidWithValidUuidAndNoActiveBanner(): void
    {
        $this->assertNull($this->articleDAO->findActiveBannerByPlayerUuid('uuid3'));
    }

    /**
     * @brief Test de la méthode findActiveBannerByPlayerUuid avec un UUID valide mais non-existant dans la base de données
     * @return void
     * @throws DateMalformedStringException
     */
    public function testFindActiveBannerByPlayerUuidWithInvalidUuid(): void
    {
        $this->assertNull($this->articleDAO->findActiveBannerByPlayerUuid('42uuid'));
    }

    /**
     * @brief Test de la méthode deleteActiveArticleForPfp avec un UUID valide mais non-existant dans la base de données
     * @return void
     */
    public function testDeleteActiveArticleForPfpWithInvalidUuid(): void
    {
        $this->expectException(PDOException::class);
        $this->articleDAO->deleteActiveArticleForPfp('42uuid');
    }

    /**
     * @brief Test de la méthode deleteActiveArticleForBanner avec un UUID valide
     * @return void
     */
    public function testDeleteActiveArticleForBannerWithValidUuid(): void
    {
        $this->articleDAO->deleteActiveArticleForBanner('uuid1');
        $this->assertNull($this->articleDAO->findActiveBannerByPlayerUuid('uuid1'));
    }

    /**
     * @brief Test de la méthode updateActiveArticle avec un UUID valide et un ID d'article valide
     * @return void
     */
    public function testUpdateActiveArticleWithValidUuidAndValidArticleId(): void
    {
        $this->articleDAO->updateActiveArticle('uuid1', '1', 'ProfilePicture');
    }

    /**
     * @brief Test de la méthode updateActiveArticle avec un UUID valide et un ID d'article invalide
     * @return void
     */
    public function testUpdateActiveArticleWithValidUuidAndInvalidArticleId(): void
    {
        $this->assertTrue($this->articleDAO->updateActiveArticle('uuid1', '42', 'ProfilePicture'));
    }

    /**
     * @brief Test de la méthode updateActiveArticle avec un UUID invalide et un ID d'article invalide
     * @return void
     */
    public function testUpdateActiveArticleWithInvalidUuidAndInvalidArticleId(): void
    {
        $this->assertTrue($this->articleDAO->updateActiveArticle('uuid48411', '6884684654', 'ProfilePicture'));
    }

    /**
     * @brief Test de la méthode updateActiveArticle avec un UUID invalide et un ID d'article valide
     * @return void
     */
    public function testUpdateActiveArticleWithInvalidUuidAndValidArticleId(): void
    {
        $this->assertTrue($this->articleDAO->updateActiveArticle('uuid187874', '42', 'ProfilePicture'));
    }

    /**
     * @brief Test de la méthode findAllPfpsOwnedByPlayer avec un UUID valide et existant dans la base de données
     * @return void
     */
    public function testFindAllPfpsOwnedByPlayerWithValidUuid(): void
    {
        $pfps = $this->articleDAO->findAllPfpsOwnedByPlayer('uuid1');
        $this->assertIsArray($pfps);
        $this->assertNotEmpty($pfps);
        $this->assertInstanceOf(Article::class, $pfps[0]);
        $this->assertEquals(ArticleType::ProfilePicture, $pfps[0]->getType());
    }

    /**
     * @brief Test de la méthode findAllPfpsOwnedByPlayer avec un UUID valide mais non-existant dans la base de données
     * @return void
     */
    public function testFindAllPfpsOwnedByPlayerWithInvalidUuid(): void
    {
        $this->assertEmpty($this->articleDAO->findAllPfpsOwnedByPlayer('42uuid'));
    }

    /**
     * @brief Test de la méthode findAllBannersOwnedByPlayer avec un UUID valide et existant dans la base de données
     * @return void
     */
    public function testFindAllBannersOwnedByPlayerWithValidUuid(): void
    {
        $banners = $this->articleDAO->findAllBannersOwnedByPlayer('uuid1');
        $this->assertIsArray($banners);
        $this->assertNotEmpty($banners);
        $this->assertInstanceOf(Article::class, $banners[0]);
        $this->assertEquals(ArticleType::Banner, $banners[0]->getType());
    }

    /**
     * @brief Test de la méthode findAllBannersOwnedByPlayer avec un UUID valide mais non-existant dans la base de données
     * @return void
     */

    public function testFindAllBannersOwnedByPlayerWithInvalidUuid(): void
    {
        $this->assertEmpty($this->articleDAO->findAllBannersOwnedByPlayer('42uuid'));
    }

    /**
     * @brief Instanciation d'un objet ArticleDAO pour les tests
     * @return void
     */
    protected function setUp(): void
    {
        $this->articleDAO = new ArticleDAO(Db::getInstance()->getConnection());
    }
}


?>