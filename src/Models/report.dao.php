<?php
/**
 * @file    report.dao.php
 * @author  Estéban DESESSARD
 * @brief   Le fichier contient la déclaration & définition de la classe ReportDAO.
 * @date    21/03/2025
 * @version 0.1
 */

namespace ComusParty\Models;

use DateMalformedStringException;
use DateTime;
use PDO;


/**
 * @brief Classe ReportDAO
 * @details La classe ReportDAO permet de gérer les actions liées aux signalements dans la base de données
 */
class ReportDAO
{
    /**
     * @brief La connexion à la base de données
     * @var PDO|null
     */
    private ?PDO $pdo;

    /**
     * @brief Le constructeur de la classe PlayerDAO
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
     * @brief Hydrate un objet Report avec les valeurs du tableau associatif passé en paramètre
     * @param array $data Le tableau associatif contenant les données à hydrater
     * @return Report L'objet Report hydraté
     * @throws DateMalformedStringException
     */
    public function hydrate(array $data): Report {
        $report = new Report();
        $report->setId($data['id']);
        $report->setObject(
            match ($data['object']) {
                'bug' => ReportObject::LANGUAGE,
                'spam' => ReportObject::SPAM,
                'links' => ReportObject::LINKS,
                'fairplay' => ReportObject::FAIRPLAY,
                default => ReportObject::OTHER
            }
        );
        $report->setDescription($data['description']);
        $report->setTreatedBy($data['treated_by']);
        $report->setReportedUuid($data['reported_uuid']);
        $report->setSenderUuid($data['sender_uuid']);
        $report->setCreatedAt(new DateTime($data['created_at']));
        $report->setUpdatedAt(new DateTime($data['updated_at']));
        return $report;
    }

    /**
     * @brief Hydrate un tableau d'objets Report avec les valeurs des tableaux associatifs du tableau passé en paramètre
     * @details Cette méthode appelle, pour chaque tableau associatif contenu dans celui passé en paramètre, la méthode hydrate() définie ci-dessus.
     * @param array $data Le tableau de tableaux associatifs
     * @return array L'objet retourné par la méthode, ici un tableau (d'objets Report)
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
    */
    public function hydrateMany(array $data) {
        $reports = [];
        foreach ($data as $row) {
            $reports[] = $this->hydrate($row);
        }
        return $reports;
    }

    /**
     * @brief Récupère tous les signalements en base de données qui ne sont pas traités
     * @return array|null Un tableau de signalements ou null si aucun signalement en attente
     * @throws DateMalformedStringException Exception levée dans le cas d'une date malformée
     */
    public function findAllWaiting(): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT *
            FROM ' . DB_PREFIX . 'report
            WHERE treated_by IS NULL'
        );
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $reportsTab = $stmt->fetchAll();
        if (!$reportsTab) {
            return null;
        }
        return $this->hydrateMany($reportsTab);
    }
}