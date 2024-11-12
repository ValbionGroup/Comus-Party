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


    /**
     * But : Retourne l'élément ciblé par l'id
     * @param string $id
     * @return Article|null
     */
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
     * But : Retourne tous les éléments de la table article
     * @return array|null
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
     * But : Retourne les avatars
     * @return array|null
     */
    public function findAllPfps() : ?array{
        $stmt = $this->pdo->query("SELECT *
        FROM ". DB_PREFIX ."article
        WHERE type = 'profile_picture'");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $tabPfps = $stmt->fetchAll();
        if($tabPfps === false){
            return null;
        }
        $pfps = $this->hydrateMany($tabPfps);
        return $pfps;

    }

    /**
     * But : Retourne toutes les bannières
     * @return array|null
     */
    public function findAllBanners() : ?array{
        $stmt = $this->pdo->query("SELECT *
        FROM ". DB_PREFIX ."article
        WHERE type = 'banner'");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $tabBanners = $stmt->fetchAll();
        if($tabBanners === false){
            return null;
        }
        $banners = $this->hydrateMany($tabBanners);
        return $banners;

    }


    /**
     * But : Transforme un tableau en objet
     * @param array $data
     * @return Article
     * @throws DateMalformedStringException
     */
    public function hydrate(array $data) : Article {
        $article = new Article();
        $article->setId($data['id']);
        $article->setName($data['name']);

        if($data['type'] == 'profile_picture'){
            $type = Type::ProfilePicture;
        }elseif ($data['type'] == 'banner'){
            $type = Type::Banner;
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
     * But : Transforme un tableau en tableau d'objets
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