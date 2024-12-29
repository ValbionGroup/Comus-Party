<?php
/**
 * @file    suggestion.dao.php
 * @author  Estéban DESESSARD
 * @brief   Le fichier contient la déclaration & définition de la classe SuggestionDAO.
 * @date    17/12/2024
 * @version 0.1
 */

namespace ComusParty\Models;

use DateMalformedStringException;
use DateTime;
use PDO;

/**
 * @brief Classe SuggestionDAO
 * @details La classe SuggestionDAO permet de gérer les suggestions en base de données
 */
class SuggestionDAO
{
    /**
     * @brief La connexion à la base de données
     * @var PDO|null
     */
    private ?PDO $pdo;

    /**
     * @brief Le constructeur de la classe SuggestionDAO
     * @param PDO|null $pdo La connexion à la base de données
     */
    public function __construct(?PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Retourne la connexion à la base de données
     * @return PDO|null Objet retourné par la méthode, ici un PDO représentant la connexion à la base de données
     */
    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

    /**
     * @brief Modifie la connexion à la base de données
     * @param PDO|null $pdo La nouvelle connexion à la base de données
     */
    public function setPdo(?PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Insert une suggestion en base de données et retourne le résultat de l'exécution
     * @param Suggestion $suggestion La suggestion à insérer
     * @return bool
     */
    public function create(Suggestion $suggestion): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO ' . DB_PREFIX . 'suggestion (object, content, author_uuid) VALUES (:object, :content, :author_uuid)');
        $object = match ($suggestion->getObject()) {
            SuggestObject::BUG => 'bug',
            SuggestObject::GAME => 'jeu',
            SuggestObject::UI => 'ui',
            SuggestObject::OTHER => 'other',
        };
        $content = $suggestion->getContent();
        $authorUuid = $suggestion->getAuthorUuid();
        $stmt->bindParam(':object', $object);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':author_uuid', $authorUuid);
        return $stmt->execute();
    }

    /**
     * @brief Récupère toutes les suggestions en base de données qui ne sont pas traitées
     * @return array|null Un tableau de suggestions ou null si aucune suggestion n'est trouvée
     */
    public function findAllWaiting(): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT s.*, p.username
            FROM ' . DB_PREFIX . 'suggestion s
            JOIN ' . DB_PREFIX . 'player p ON s.author_uuid = p.uuid
            WHERE s.treated_by IS NULL'
        );
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $suggestsTab = $stmt->fetchAll();
        if (!$suggestsTab) {
            return null;
        }
        return $this->hydrateMany($suggestsTab);
    }

    /**
     * @brief Hydrate un tableau de données en plusieurs suggestions
     * @param array $suggestsTab Le tableau associatif contenant les tableaux de données des suggestions
     * @return array Un tableau d'objets Suggestion
     */
    private function hydrateMany(array $suggestsTab): array
    {
        $suggests = [];
        if ($suggestsTab) {
            foreach ($suggestsTab as $suggestTab) {
                $suggests[] = $this->hydrate($suggestTab);
            }
        }
        return $suggests;
    }

    /**
     * @brief Hydrate un tableau de données en une suggestion
     * @param array $data Le tableau associatif contenant les données de la suggestion
     * @return Suggestion Objet retourné par la méthode, ici une suggestion
     * @throws DateMalformedStringException Exceptions levée dans le cas d'une date malformée
     */
    public function hydrate(array $data): Suggestion
    {
        $suggestion = new Suggestion();
        $suggestion->setId($data['id']);
        $suggestion->setObject(
            match ($data['object']) {
                'bug' => SuggestObject::BUG,
                'jeu' => SuggestObject::GAME,
                'ui' => SuggestObject::UI,
                'other' => SuggestObject::OTHER,
            }
        );
        $suggestion->setContent($data['content']);
        $suggestion->setAuthorUuid($data['author_uuid']);
        $suggestion->setCreatedAt(new DateTime($data['created_at']));
        $suggestion->setAuthorUsername($data['username']);
        return $suggestion;
    }

    /**
     * @brief Refuse une suggestio en modifiant un attribut en base de données et retourne le résultat de l'exécution
     * @details L'attribut modifié st :
     *   - treated_by : replacement par l'uuid de l'utilisateur connecté
     *   (l'attrbiut threated_at est mis à jour automatiquement par la base de données)
     * @param int|null $id L'identifiant de la suggestion à refuser
     * @return bool Résultat de l'exécution
     */
    public function deny(?int $id): bool
    {
        $stmt = $this->pdo->prepare('UPDATE ' . DB_PREFIX . 'suggestion SET treated_by = :treated_by WHERE id = :id');
        $stmt->bindParam(':treated_by', $_SESSION['uuid']);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    /**
     * @brief Accepte une suggestio en modifiant ses attributs en base de données et retourne le résultat de l'exécution
     * @details Les attributs modifiés sont :
     *   - treated_by : replacement par l'uuid de l'utilisateur connecté,
     *   - accepted : 1
     *   (l'attrbiut threated_at est mis à jour automatiquement par la base de données)
     * @param int|null $id L'identifiant de la suggestion à accepter
     * @return bool Résultat de l'exécution
     */
    public function accept(?int $id): bool
    {
        $stmt = $this->pdo->prepare('UPDATE ' . DB_PREFIX . 'suggestion SET treated_by = :treated_by, accepted = 1 WHERE id = :id');
        $stmt->bindParam(':treated_by', $_SESSION['uuid']);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    /**
     * @brief Récupère une suggestion en base de données par son identifiant
     * @param int|null $id L'identifiant de la suggestion
     * @return Suggestion|null La suggestion ou null si aucune suggestion n'est trouvée
     * @throws DateMalformedStringException Exceptions levée dans le cas d'une date malformée
     */
    public function findById(?int $id)
    {
        $stmt = $this->pdo->prepare(
            'SELECT s.*, p.username
            FROM ' . DB_PREFIX . 'suggestion s
            JOIN ' . DB_PREFIX . 'player p ON s.author_uuid = p.uuid
            WHERE s.id = :id'
        );
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $suggestTab = $stmt->fetch();
        if (!$suggestTab) {
            return null;
        }
        return $this->hydrate($suggestTab);
    }
}