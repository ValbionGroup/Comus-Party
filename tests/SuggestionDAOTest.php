<?php
/**
 * @file SuggestionDAOTest.php
 * @brief Le fichier contient la déclaration et la définition de la classe SuggestionDAO
 * @author DESESSARD Estéban
 * @date 17/12/2024
 * @version 0.1
 */

use ComusParty\Models\Db;
use ComusParty\Models\Suggestion;
use ComusParty\Models\SuggestionDAO;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../include.php';

/**
 * @brief Classe ArticleDAOTest
 * @details La classe ArticleDAOTest permet de tester les méthodes de la classe ArticleDAO
 */
class SuggestionDAOTest extends TestCase
{
    /**
     * @brief SuggestionDAO
     * @var SuggestionDAO
     */
    private SuggestionDAO $suggestionDAO;

    /**
     * @brief Test de la méthode create avec un UUID existant
     * @return void
     */
    public function testCreateWithExistantUuid(): void
    {
        $suggestion = new Suggestion(
            content: 'TestContent',
            authorUuid: 'uuid1'
        );
        $this->assertTrue($this->suggestionDAO->create($suggestion));
    }

    /**
     * @brief Test de la méthode create avec un UUID non existant
     * @return void
     */
    public function testCreateWithNonExistantUuid(): void
    {
        $suggestion = new Suggestion(
            content: 'TestContent',
            authorUuid: 'uuid69'
        );
        $this->expectException(PDOException::class);
        $this->suggestionDAO->create($suggestion);
    }

    /**
     * @brief Test de la méthode create avec un uuid null
     * @return void
     */
    public function testCreateWithNullUuid(): void
    {
        $suggestion = new Suggestion(
            content: 'TestContent',
            authorUuid: null
        );
        $this->expectException(PDOException::class);
        $this->suggestionDAO->create($suggestion);
    }

    /**
     * @brief Instanciation d'un objet SuggestionDAO pour les tests
     * @return void
     */
    protected function setUp(): void
    {
        $this->suggestionDAO = new SuggestionDAO(Db::getInstance()->getConnection());
    }
}
