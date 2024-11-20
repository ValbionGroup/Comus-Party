<?php
/**
 * @file ArticleDAOTest.php
 * @brief Le fichier contient la déclaration et la définition de la classe ArticleDAOTest
 * @author DESESSARD Estéban
 * @date 20/11/2024
 * @version 0.1
 */

use PHPUnit\Framework\TestCase;

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
     * @brief Instanciation d'un objet ArticleDAO pour les tests
     * @return void
     */
    protected function setUp(): void
    {
        $this->playerDAO = new ArticleDAO(Db::getInstance()->getConnection());
    }
}
