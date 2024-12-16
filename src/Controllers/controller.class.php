<?php
/**
 * @file    controller.class.php
 * @author  Estéban DESESSARD
 * @brief   Le fichier contient la déclaration & définition de la classe Controller.
 * @date    12/11/2024
 * @version 0.1
 */

namespace ComusParty\Controllers;

use ComusParty\Models\Db;
use ComusParty\Models\Exception\AuthenticationException;
use ComusParty\Models\Exception\ErrorHandler;
use ComusParty\Models\Exception\MethodNotFoundException;
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

        if (!empty($_GET)) {
            $this->get = $_GET;
        }

        if (!empty($_POST)) {
            $this->post = $_POST;
        }
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
                    ErrorHandler::addExceptionParametersToSession($e);
                    header('Location: /login');
                    break;
                default:
                    ErrorHandler::displayFullScreenException($e);
                    break;
            }
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