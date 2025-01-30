<?php
/**
 * @file    controller.class.php
 * @author  Estéban DESESSARD
 * @brief   Le fichier contient la déclaration & définition de la classe Controller.
 * @date    12/11/2024
 * @version 0.1
 */

namespace ComusParty\Controllers;

use ComusParty\App\Db;
use ComusParty\App\Exceptions\AuthenticationException;
use ComusParty\App\Exceptions\MethodNotFoundException;
use ComusParty\App\MessageHandler;
use Error;
use Exception;
use PDO;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * @brief Classe Controller
 * @details La classe Controller est la classe mère de tous les contrôleurs
 */
class Controller
{
    /**
     * @brief La connexion à la base de données
     * @var PDO
     */
    private PDO $pdo;

    /**
     * @brief Le loader de Twig
     * @var FilesystemLoader
     */
    private FilesystemLoader $loader;

    /**
     * @brief L'environnement de Twig
     * @var Environment
     */

    private Environment $twig;
    /**
     * @brief Les données passées en paramètre via la méthode GET
     * @var array|null
     */
    private ?array $get = null;

    /**
     * @brief Les données passées en paramètre via la méthode POST
     * @var array|null
     */
    private ?array $post = null;

    /**
     * @brief Le constructeur de la classe Controller
     * @param FilesystemLoader $loader Le loader de Twig
     * @param Environment $twig L'environnement de Twig
     */
    public function __construct(FilesystemLoader $loader, Environment $twig)
    {
        $this->pdo = Db::getInstance()->getConnection();
        $this->loader = $loader;
        $this->twig = $twig;

        if (BACKUP_ENABLE && BACKUP_MODE === 'manual') {
            $this->makeDatabaseBackup();
        }

        if (!empty($_GET)) {
            $this->get = $_GET;
        }

        if (!empty($_POST)) {
            $this->post = $_POST;
        }
    }

    private function makeDatabaseBackup(): void
    {
        $minutesBetweenBackups = BACKUP_INTERVAL;
        $isBackupNeeded = false;

        if (file_exists(__DIR__ . '/../../backup')) {
            $files = glob(__DIR__ . '/../../backup/db-backup_*.sql');
            usort($files, function ($a, $b) {
                return filemtime($a) < filemtime($b);
            });

            if (count($files) > 0) {
                $lastBackup = $files[0];
                $lastBackupTime = filemtime($lastBackup);
                $currentTime = time();
                $diff = $currentTime - $lastBackupTime;
                $minutes = $diff / 60;

                if ($minutes >= $minutesBetweenBackups) {
                    $isBackupNeeded = true;
                }

                # Suppression des anciennes sauvegardes
                if (count($files) > BACKUP_RETENTION) {
                    for ($i = BACKUP_RETENTION; $i < count($files); $i++) {
                        unlink($files[$i]);
                    }
                }
            } else {
                $isBackupNeeded = true;
            }
        } else {
            $isBackupNeeded = true;
        }

        if (!$isBackupNeeded) {
            return;
        }

        try {
            $date = date('Y-m-d_H-i-s');
            $backupFile = 'db-backup_' . $date . '.sql';

            $stmt = $this->getPdo()->prepare('SHOW TABLES');
            $stmt->execute();
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);


            $sql = 'CREATE DATABASE IF NOT EXISTS `' . DB_NAME . '`' . ";\n\n"; // Création de la base de données
            $sql .= 'USE `' . DB_NAME . "`;\n\n"; // Utilisation de la base de données pour la suite des opérations
            $sql .= "SET foreign_key_checks = 0;\n\n"; // Désactivation des contraintes de clés étrangères

            foreach ($tables as $table) {
                $sql .= 'DROP TABLE IF EXISTS `' . $table . '`;';

                $stmt = $this->getPdo()->prepare('SHOW CREATE TABLE `' . $table . '`');
                $stmt->execute();
                $sql .= "\n\n" . $stmt->fetchColumn(1) . ";\n\n";

                $stmt = $this->getPdo()->prepare('SELECT COUNT(*) FROM `' . $table . '`');
                $stmt->execute();
                $count = $stmt->fetchColumn();
                $numBatches = intval($count / BACKUP_FETCH_LIMIT) + 1;

                for ($b = 1; $b <= $numBatches; $b++) {
                    $request = 'SELECT * FROM `' . $table . '` LIMIT ' . ($b * BACKUP_FETCH_LIMIT - BACKUP_FETCH_LIMIT) . ',' . BACKUP_FETCH_LIMIT;
                    $stmt = $this->getPdo()->prepare($request);
                    $stmt->execute();

                    $realBatchSize = $stmt->rowCount();
                    $numFields = $stmt->columnCount();

                    if ($realBatchSize !== 0) {
                        $sql .= 'INSERT INTO `' . $table . '` VALUES ';

                        for ($i = 0; $i < $numFields; $i++) {
                            $rowCount = 1;
                            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                                $sql .= '(';
                                for ($j = 0; $j < $numFields; $j++) {
                                    if (isset($row[$j])) {
                                        $row[$j] = addslashes($row[$j]);
                                        $row[$j] = str_replace("\n", "\\n", $row[$j]);
                                        $row[$j] = str_replace("\r", "\\r", $row[$j]);
                                        $row[$j] = str_replace("\f", "\\f", $row[$j]);
                                        $row[$j] = str_replace("\t", "\\t", $row[$j]);
                                        $row[$j] = str_replace("\v", "\\v", $row[$j]);
                                        $row[$j] = str_replace("\a", "\\a", $row[$j]);
                                        $row[$j] = str_replace("\b", "\\b", $row[$j]);
                                        if ($row[$j] == 'true' or $row[$j] == 'false' or preg_match('/^-?[1-9][0-9]*$/', $row[$j]) or $row[$j] == 'NULL' or $row[$j] == 'null') {
                                            $sql .= $row[$j];
                                        } else {
                                            $sql .= '\'' . $row[$j] . '\'';
                                        }
                                    } else {
                                        $sql .= 'NULL';
                                    }

                                    if ($j < ($numFields - 1)) {
                                        $sql .= ',';
                                    }
                                }

                                if ($rowCount == $realBatchSize) {
                                    $rowCount = 0;
                                    $sql .= ");\n"; // Fermeture de l'instruction d'insertion
                                } else {
                                    $sql .= "),\n"; // Fermeture de la ligne d'insertion
                                }

                                $rowCount++;
                            }
                        }
                    }
                }
            }

            $sql .= "SET foreign_key_checks = 1;\n";

            if (!is_dir(__DIR__ . '/../../backup')) {
                mkdir(__DIR__ . '/../../backup');
            }

            file_put_contents(__DIR__ . '/../../backup/' . $backupFile, $sql);
        } catch (Exception $e) {
            if (!is_dir(__DIR__ . '/../../backup')) {
                mkdir(__DIR__ . '/../../backup');
            }

            file_put_contents(__DIR__ . '/../../backup/error.txt', $e->getMessage());
        }
    }

    /**
     * @brief Retourne l'attribut PDO, correspondant à la connexion à la base de données
     * @return PDO Objet retourné par la méthode, ici un PDO représentant la connexion à la base de données
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * @brief Modifie l'attribut PDO, correspondant à la connexion à la base de données
     * @param PDO $pdo La nouvelle connexion à la base de données
     * @return void
     */
    public function setPdo(PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Appelle la méthode du Controller passée en paramètre
     * @param string $method La méthode à appeler
     * @param array|null $args Les arguments à passer à la méthode
     * @return mixed  Le résultat de la méthode appelée
     * @throws MethodNotFoundException Exception levée dans le cas où la méhode n'existe pas
     * @todo Vérifier le reste du traitement de l'exception (Cf PR 64 GitHub)
     */
    public function call(string $method, ?array $args = []): mixed
    {
        if (!method_exists($this, $method) || !is_callable([$this, $method])) {
            throw new MethodNotFoundException('La méthode ' . $method . ' n\'existe pas dans le contrôleur ' . get_class($this));
        }

        try {
            return $this->{$method}(...array_values($args));
        } catch (Exception $e) {
            switch ($e::class) {
                case AuthenticationException::class:
                    MessageHandler::addExceptionParametersToSession($e);
                    header('Location: /login');
                    break;
                default:
                    MessageHandler::displayFullScreenException($e);
                    break;
            }
        } catch (Error $e) {
            MessageHandler::displayFullScreenError($e);
        }

        return null;
    }

    /**
     * @brief Retourne l'attribut PDO, correspondant à la connexion à la base de données
     * @return PDO Objet retourné par la méthode, ici un PDO représentant la connexion à la base de données
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * @brief Modifie l'attribut PDO, correspondant à la connexion à la base de données
     * @param PDO $pdo La nouvelle connexion à la base de données
     * @return void
     */
    public function setPdo(PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    /**
     * @brief Retourne l'attribut loader, correspondant au loader de Twig
     * @return FilesystemLoader Object retourné par la méthode, ici un FilesystemLoader représentant le loader de Twig
     */
    public function getLoader(): FilesystemLoader
    {
        return $this->loader;
    }

    /**
     * @brief Modifie l'attribut loader, correspondant au loader de Twig
     * @param FilesystemLoader $loader Le nouveau loader de Twig
     * @return void
     */
    public function setLoader(FilesystemLoader $loader): void
    {
        $this->loader = $loader;
    }

    /**
     * @brief Retourne l'attribut twig, correspondant à l'environnement de Twig
     * @return Environment Objet retourné par la méthode, ici un Environment représentant l'environnement de Twig
     */
    public function getTwig(): Environment
    {
        return $this->twig;
    }

    /**
     * @brief Modifie l'attribut twig, correspondant à l'environnement de Twig
     * @param Environment $twig Le nouvel environnement de Twig
     * @return void
     */
    public function setTwig(Environment $twig): void
    {
        $this->twig = $twig;
    }

    /**
     * @brief Retourne l'attribut GET, correspondant aux données passées en paramètre via la méthode GET
     * @return array|null Objet retourné par la méthode, ici un tableau associatif représentant les données passées en paramètre via la méthode GET
     */
    public function getGet(): ?array
    {
        return $this->get;
    }

    /**
     * @brief Modifie l'attribut GET, correspondant aux données passées en paramètre via la méthode GET
     * @param array|null $get Le nouveau tableau associatif représentant les données passées en paramètre via la méthode GET
     * @return void
     */
    public function setGet(?array $get): void
    {
        $this->get = $get;
    }

    /**
     * @brief Retourne l'attribut POST, correspondant aux données passées en paramètre via la méthode POST
     * @return array|null Objet retourné par la méthode, ici un tableau associatif représentant les données passées en paramètre via la méthode POST
     */
    public function getPost(): ?array
    {
        return $this->post;
    }

    /**
     * @brief Modifie l'attribut POST, correspondant aux données passées en paramètre via la méthode POST
     * @param array|null $post Le nouveau tableau associatif représentant les données passées en paramètre via la méthode POST
     * @return void
     */
    public function setPost(?array $post): void
    {
        $this->post = $post;
    }
}