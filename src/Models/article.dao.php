<?php
/**
 * @file    article.dao.php
 * @author  Mathis RIVRAIS--NOWAKOWSKI
 * @brief   Le fichier contient la déclaration & définition de la classe ArticleDAO.
 * @date    13/11/2024
 * @version 0.2
 */

namespace ComusParty\Models;


use ComusParty\Models\Exception\NotFoundException;
use DateMalformedStringException;
use DateTime;
use Exception;
use PDO;

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
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $article = $stmt->fetch();
        if ($article === false) {
            return null;
        }
        return $this->hydrate($article);
    }

    /**
     * @brief Retourne un tableau d'objets Article (ou null) à partir de l'ID de la facture passé en paramètre
     * @param int|null $invoiceId L'ID de la facture
     * @return array|null Objet retourné par la méthode, ici un tableau d'objets Article (ou null si non-trouvé)
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     * @throws NotFoundException Exception levée dans le cas où la facture n'existe pas
     */
    public function findArticlesByInvoiceId(?int $invoiceId): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT *
          FROM ' . DB_PREFIX . 'invoice i
          JOIN ' . DB_PREFIX . 'player p ON i.player_uuid = p.uuid
          WHERE i.id = :id AND p.uuid = :uuid');
        $stmt->bindParam(':id', $invoiceId);
        $stmt->bindParam(':uuid', $_SESSION['uuid']);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $invoice = $stmt->fetch();
        if ($invoice === false) {
            throw new NotFoundException('Cette facture n\'existe pas.');
        }

        $stmt = $this->pdo->prepare(
            'SELECT a.*
            FROM ' . DB_PREFIX . 'article a
            JOIN ' . DB_PREFIX . 'invoice_row ir ON a.id = ir.article_id
            WHERE ir.invoice_id = :invoice_id');
        $stmt->bindParam(':invoice_id', $invoiceId);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $articles = $stmt->fetchAll();
        if ($articles === false) {
            return null;
        }
        return $this->hydrateMany($articles);
    }

    /**
     * @brief Retourne un tableau d'objets Article (ou null) à partir de l'ID de l'user correspondants à l'ensemble des photos de profil possédées
     * @return array|null Objet retourné par la méthode, ici un tableau d'objets Article (ou null si non-trouvé)
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     * @throws NotFoundException Exception levée dans le cas où la facture n'existe pas
     */
    public function findAllPfpsByUuidPlayer(string $uuid): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT a.*
            FROM ' . DB_PREFIX . 'article a
            JOIN ' . DB_PREFIX . 'invoice_row ir ON a.id = ir.article_id
            JOIN ' . DB_PREFIX . 'invoice i ON ir.invoice_id = i.id
            WHERE i.player_uuid = :uuid AND type = "pfp" ');
        $stmt->bindParam(':uuid', $uuid);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $articles = $stmt->fetchAll();
        return $this->hydrateMany($articles);
    }

    /**
     * @brief Retourne un tableau d'objets Article recensant l'ensemble des articles enregistrés dans la base de données
     * @return array|null Objet retourné par la méthode, ici un tableau d'objets Article (ou null si aucune article recensé)
     * @warning Cette méthode retourne un tableau contenant autant d'objet qu'il y a d'articles dans la base de données, pouvant ainsi entraîner la manipulation d'un grand set de données.
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
        return $this->hydrateMany($tabArticles);
    }

    /**
     * @brief Retourne un tableau d'objets Article recensant l'ensemble des articles correspondant aux id "ids"
     * @param array $ids Le tableau contenant les ids des articles qu'on veut obtenir
     * @return array|null Objet retourné par la méthode, ici un tableau d'objets Article (ou null si aucune article recensé)
     * @warning Cette méthode retourne un tableau contenant autant d'objet qu'il y a d'id d'articles dans le tableau, pouvant ainsi entraîner la manipulation d'un grand set de données.
     */
    function findArticlesWithIds($ids)
    {

        if (!empty($ids)) {
            $idsString = implode(',', $ids);

            $stmt = $this->pdo->query('SELECT * FROM '. DB_PREFIX . 'article WHERE id IN ('.$idsString.')');
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $tabArticles = $stmt->fetchAll();

            if ($tabArticles === false) {
                return null;
            }
            $articles = $this->hydrateMany($tabArticles);
            return $articles;
        }
        return null;
    }

    /**
     * @brief Retourne un tableau d'objets Article qui ont le type profile_picture dans la base de données
     * @return array|null Objet retourné par la méthode, ici un tableau d'objets Article qui ont le type pfp (ou null si aucun Article avec le type pfp recensé)
     * @warning Cette méthode retourne un tableau contenant autant d'objet qu'il y a d'articles avec le type profile_picture dans la base de données, pouvant ainsi entraîner la manipulation d'un grand set de données.
     * @throws DateMalformedStringException
     */

    public function findAllPfps() : ?array{
        $stmt = $this->pdo->query("SELECT *
        FROM ". DB_PREFIX ."article
        WHERE type = 'pfp'");

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $tabPfps = $stmt->fetchAll();

        if($tabPfps === false){
            return null;
        }
        return $this->hydrateMany($tabPfps);
    }

    /**
     * @brief Retourne un tableau d'objets Article qui ont le type banner dans la base de données
     * @return array|null Objet retourné par la méthode, ici un tableau d'objets Article qui ont le type banner (ou null si aucune joueur recensé)
     * @warning Cette méthode retourne un tableau contenant autant d'objet qu'il y a d'articles avec le type banner dans la base de données, pouvant ainsi entraîner la manipulation d'un grand set de données.
     * @throws DateMalformedStringException
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
        return $this->hydrateMany($tabBanners);

    }
    /**
     * @brief Met à jour l'article en active dans la base de données
     * @param string $uuid L'UUID du joueur
     * @param string $idArticle L'ID de l'article
     */
    public function updateActiveArticle(string $uuid, string $idArticle)
    {
        $pfpActive = $this->findActivePfpByPlayerUuid($uuid);
        // Si pfp déjà équipé
        if($pfpActive != null){
            $idPfpActive = $pfpActive->getId();
            $stmt = $this->pdo->prepare(
                'UPDATE '. DB_PREFIX . 'invoice_row ir
        JOIN ' . DB_PREFIX . 'invoice i ON ir.invoice_id = i.id
        JOIN ' . DB_PREFIX . 'article a ON ir.article_id = a.id
        SET ir.active = 0
        WHERE i.player_uuid = :uuid AND ir.article_id = :idArticleActif');
            $stmt->bindParam(':uuid', $uuid);
            $stmt->bindParam(':idArticleActif', $idPfpActive);
            $stmt->execute();
        }

        $stmt = $this->pdo->prepare(
            'UPDATE '. DB_PREFIX . 'invoice_row ir
        JOIN ' . DB_PREFIX . 'invoice i ON ir.invoice_id = i.id
        JOIN ' . DB_PREFIX . 'article a ON ir.article_id = a.id
        SET ir.active = 1
        WHERE i.player_uuid = :uuid AND ir.article_id = :idArticle'
        );
        $stmt->bindParam(':uuid', $uuid);
        $stmt->bindParam(':idArticle', $idArticle);
        $stmt->execute();
        $article = $this->findById($idArticle);
        $_SESSION['pfpPath'] = $article->getFilePath();
    }

    /**
     * @brief Supprime toutes les pfps pour les mettre à 0 en active
     * @param string $uuid L'UUID du joueur
     */
    public function deleteActiveArticleForPfp(string $uuid)
    {
        $stmt = $this->pdo->prepare(
            'UPDATE '. DB_PREFIX . 'invoice_row ir
        JOIN ' . DB_PREFIX . 'invoice i ON ir.invoice_id = i.id
        JOIN ' . DB_PREFIX . 'article a ON ir.article_id = a.id
        SET ir.active = 0 
        WHERE i.player_uuid = :uuid AND a.type = "pfp"');
        $stmt->bindParam(':uuid', $uuid);
        $stmt->execute();
    }

    /**
     * @brief Retourne la photo de profile active que le joueur possède sous forme d'objet Article
     * @param string $uuid L'UUID du joueur
     * @return Article|null La photo de profil du joueur (ou null si non-trouvée)
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     */
    public function findActivePfpByPlayerUuid(string $uuid): ?Article
    {
        $stmt = $this->pdo->prepare(
            'SELECT a.*
            FROM ' . DB_PREFIX . 'article a
            JOIN ' . DB_PREFIX . 'invoice_row ir ON a.id = ir.article_id
            JOIN ' . DB_PREFIX . 'invoice i ON ir.invoice_id = i.id
            WHERE i.player_uuid = :uuid AND a.type = "pfp" AND ir.active = 1');
        $stmt->bindParam(':uuid', $uuid);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $pfp = $stmt->fetch();
        if ($pfp === false) {
            return null;
        }
        return $this->hydrate($pfp);
    }

    /**
     * @brief Retourne la bannière active que le joueur possède sous forme d'objet Article
     * @param string $uuid L'UUID du joueur
     * @return Article|null La bannière du joueur (ou null si non-trouvée)
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     */
    public function findActiveBannerByPlayerUuid(string $uuid): ?Article
    {
        $stmt = $this->pdo->prepare(
            'SELECT a.*
            FROM ' . DB_PREFIX . 'article a
            JOIN ' . DB_PREFIX . 'invoice_row ir ON a.id = ir.article_id
            JOIN ' . DB_PREFIX . 'invoice i ON ir.invoice_id = i.id
            WHERE i.player_uuid = :uuid AND a.type = "banner" AND ir.active = 1');
        $stmt->bindParam(':uuid', $uuid);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $banner = $stmt->fetch();
        if ($banner === false) {
            return null;
        }
        return $this->hydrate($banner);
    }


    /**
     * @brief Hydrate un objet Article avec les valeurs du tableau associatif passé en paramètre
     * @param array $data Le tableau associatif content les paramètres
     * @return Article L'objet retourné par la méthode, ici un article
     * @throws DateMalformedStringException|Exception Exception levée dans le cas d'une date malformée
     */
    public function hydrate(array $data) : Article {
        $article = new Article();
        $article->setId($data['id']);
        $article->setName($data['name']);

        if ($data['type'] == 'pfp') {
            $type = ArticleType::ProfilePicture;
        }elseif ($data['type'] == 'banner'){
            $type = ArticleType::Banner;
        }

        $article->setType($type);
        $article->setDescription($data['description']);
        $article->setPricePoint($data['price_point']);
        $article->setPriceEuro($data['price_euro']);

        $article->setCreatedAt(new DateTime($data['created_at']));
        $article->setUpdatedAt(new DateTime($data['updated_at']));
        $article->setFilePath($data['file_path']);

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