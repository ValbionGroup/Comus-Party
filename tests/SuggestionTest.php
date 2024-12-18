<?php
/**
 * @file SuggestionTest.php
 * @brief Fichier de test des suggestions
 * @author Estéban DESESSARD
 * @date 17/12/2024
 * @version 1.0
 */

require_once __DIR__ . '/../include.php';

use ComusParty\Models\Suggestion;
use PHPUnit\Framework\TestCase;

class SuggestionTest extends TestCase
{
    private Suggestion $suggestion;

    public function testGetContent(): void
    {
        $this->assertEquals('Ajouter une interface dynamique', $this->suggestion->getContent());
    }

    public function testSetContentWithValidContent(): void
    {
        $this->suggestion->setContent('Ajouter une interface statique');
        $this->assertEquals('Ajouter une interface statique', $this->suggestion->getContent());
    }

    public function testGetAuthorUuid(): void
    {
        $this->assertEquals('uuid1', $this->suggestion->getAuthorUuid());
    }

    public function testSetAuthorUuidWithValidUuid(): void
    {
        $this->suggestion->setAuthorUuid('uuid2');
        $this->assertEquals('uuid2', $this->suggestion->getAuthorUuid());
    }

    public function testSetAuthorUuidWithNullUuid(): void
    {
        $this->suggestion->setAuthorUuid(null);
        $this->assertNull($this->suggestion->getAuthorUuid());
    }

    public function testSetAuthorUuidWithEmptyUuid(): void
    {
        $this->suggestion->setAuthorUuid('');
        $this->assertEmpty($this->suggestion->getAuthorUuid());
    }

    protected function setUp(): void
    {
        $this->suggestion = new Suggestion(
            content: 'Ajouter une interface dynamique',
            authorUuid: 'uuid1',
        );
    }
}
