<?php
/**
 * @file    article.dao.php
 * @author  Mathis RIVRAIS--NOWAKOWSKI
 * @brief   Le fichier contient la déclaration & définition de la classe ArticleDAO.
 * @date    13/11/2024
 * @version 0.1
 */




/**
 * @brief Classe ArticleDAO
 * @details La classe ArticleDAO permet de faire des opérations sur la table article dans la base de données
 */
class ArticleDAO {
    /**
     * @brief La connexion à la base de données
     * @var PDO|null
     */
    private ?PDO $pdo;


    /**
     * @brief Le constructeur de la classe ArticleDAO
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
     * @brief Retourne un objet Article (ou null) à partir de l'ID passé en paramètre
     * @param string $id L'ID de l'article recherché
     * @return Article|null Objet retourné par la méthode, ici un article (ou null si non-trouvé)
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
     * @brief Retourne un tableau d'objets Article recensant l'ensemble des articles enregistrés dans la base de données
     * @return array|null Objet retourné par la méthode, ici un tableau d'objets Article (ou null si aucune article recensé)
     * @warning Cette méthode retourne un tableau contenant autant d'objet qu'il y a d'articles dans la base de données, pouvant ainsi entraîner la manipulation d'un grand set de données.
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
     * @brief Retourne un tableau d'objets Article qui ont le type profile_picture dans la base de données
     * @return array|null Objet retourné par la méthode, ici un tableau d'objets Article qui ont le type profile_picture (ou null si aucune joueur recensé)
     * @warning Cette méthode retourne un tableau contenant autant d'objet qu'il y a d'articles avec le type profile_picture dans la base de données, pouvant ainsi entraîner la manipulation d'un grand set de données.
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
     * @brief Retourne un tableau d'objets Article qui ont le type banner dans la base de données
     * @return array|null Objet retourné par la méthode, ici un tableau d'objets Article qui ont le type banner (ou null si aucune joueur recensé)
     * @warning Cette méthode retourne un tableau contenant autant d'objet qu'il y a d'articles avec le type banner dans la base de données, pouvant ainsi entraîner la manipulation d'un grand set de données.
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
     * @brief Hydrate un objet Article avec les valeurs du tableau associatif passé en paramètre
     * @param array $data Le tableau associatif content les paramètres
     * @return Article L'objet retourné par la méthode, ici un article
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
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
     * @brief Hydrate un tableau d'objets Article avec les valeurs des tableaux associatifs du tableau passé en paramètre
     * @details Cette méthode appelle, pour chaque tableau associatif contenu dans celui passé en paramètre, la méthode hydrate() définie ci-dessus.
     * @param array $data Le tableau de tableaux associatifs
     * @return array L'objet retourné par la méthode, ici un tableau (d'objets Article)
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     */
    public function hydrateMany(array $data) : array {
        $articles = [];
        foreach ($data as $article) {
            $articles[] = $this->hydrate($article);
        }
        return $articles;
    }
}