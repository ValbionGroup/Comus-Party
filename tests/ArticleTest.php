<?php
/**
 * @file ArticleTest.php
 * @brief Le fichier contient la déclaration et la définition de la classe ArticleTest
 * @author DESESSARD Estéban
 * @date 20/11/2024
 * @version 0.1
 */

use PHPUnit\Framework\TestCase;

/**
 * @brief La classe ArticleTest permet de tester les méthodes de la classe Article
 */
class ArticleTest extends TestCase
{
    /**
     * @brief Article
     * @var Article
     */
    private Article $article;

    protected function setUp(): void
    {
        /**
         * @brief Instanciation d'un objet Article pour les tests
         */
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

        )
    }
}
