<?php


/**
 * La classe ArticleDAO permet de faire des opérations sur la table article
 */
class ArticleDAO {
    /**
     * Classe PDO pour la connexion à la base de données
     *
     * @var PDO|null
     */
    private ?PDO $pdo;

    /**
     * Constructeur de la classe PlayerDAO
     *
     * @param PDO|null $pdo
     */
    public function __construct(?PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Retourne la connexion à la base de données
     *
     * @return PDO|null
     */
    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

    /**
     * Modifie la connexion à la base de données
     *
     * @param PDO|null $pdo
     */
    public function setPdo(?PDO $pdo): void
    {
        $this->pdo = $pdo;
    }


    public function findById(string $id): ?Article {
        $stmt = $this->pdo->prepare(
            'SELECT *
            FROM '. DB_PREFIX .'article
            WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Article');
        $article = $stmt->fetch();
        if ($article === false) {
            return null;
        }
        return $article;
    }

    /**
     * Retourne un objet Player (ou null) à partir de l'UUID passé en paramètre avec les détails de l'utilisateur associé
     *
     * @param string $uuid
     * @return Player|null
     * @throws DateMalformedStringException
     */
//    public function findWithDetailByUuid(string $uuid): ?Player {
//        $stmt = $this->pdo->prepare(
//            'SELECT p.*, u.username, u.email, u.created_at, u.updated_at
//            FROM '. DB_PREFIX .'player p
//            JOIN '. DB_PREFIX .'user u ON p.user_id = u.id
//            WHERE p.uuid = :uuid');
//        $stmt->bindParam(':uuid', $uuid);
//        $stmt->execute();
//        $stmt->setFetchMode(PDO::FETCH_ASSOC);
//        $tabPlayer = $stmt->fetch();
//        if ($tabPlayer === false) {
//            return null;
//        }
//        $player = $this->hydrate($tabPlayer);
//        return $player;
//    }

    /**
     * Retourne un objet Player (ou null) à partir de l'identifiant utilisateur passé en paramètre avec les détails de l'utilisateur associé
     *
     * @param int $userId
     * @return Player|null
     */


    /**
     * Retourne un tableau d'objets Player recensant l'ensemble des joueurs enregistrés dans la base de données
     *
     * ⚠️ : Cette méthode retourne un tableau contenant autant d'objet qu'il y a de joueurs dans la base de données, pouvant ainsi entraîner la manipulation d'un grand set de données.
     *
     * @return array
     * @throws DateMalformedStringException
     */
    public function findAll() : ?array {
        $stmt = $this->pdo->query(
            'SELECT *
            FROM '. DB_PREFIX .'article');
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $tabArticles = $stmt->fetchAll();
        if ($tabArticles === false) {
            return null;
        }
        $articles = $this->hydrateMany($tabArticles);
        return $articles;
    }




    /**
     * Retourne un objet Player valorisé avec les valeurs du tableau associatif passé en paramètre
     *
     * @param array $data
     * @return Player
     * @throws DateMalformedStringException
     */
    public function hydrate(array $data) : Article {
        $article = new Article();
        $article->setId($data['id']);
        $article->setName($data['name']);

        if($data['type'] == 'profil_picture'){
            $type = Type::ProfilePicture;
        }

        $article->setType($type);
        $article->setDescription($data['description']);
        $article->setPricePoint($data['price_point']);
        $article->setPriceEuro($data['price_euro']);

        $article->setCreatedAt(new DateTime($data['created_at']));
        $article->setUpdatedAt(new DateTime($data['updated_at']));
        $article->setPathImg($data['path_img']);

        return $article;
    }

    /**
     * Retourne un tableau d'objets Player valorisés avec les valeurs du tableau de tableaux associatifs passé en paramètre
     *
     * @param array $data
     * @return array
     * @throws DateMalformedStringException
     */
    public function hydrateMany(array $data) : array {
        $articles = [];
        foreach ($data as $article) {
            $articles[] = $this->hydrate($article);
        }
        return $articles;
    }
}