<?php

use models\MethodNotFoundException;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * La classe Controller est la classe mère de tous les contrôleurs
 */
class Controller {
    /**
     * La connexion à la base de données
     *
     * @var PDO
     */
    private PDO $pdo;

    /**
     * Le loader de Twig
     *
     * @var FilesystemLoader
     */
    private FilesystemLoader $loader;

    /**
     * L'environnement de Twig
     *
     * @var Environment
     */

    private Environment $twig;
    /**
     * Les données GET
     *
     * @var array|null
     */
    private ?array $get = null;

    /**
     * Les données POST
     *
     * @var array|null
     */
    private ?array $post = null;

    /**
     * Constructeur de la classe Controller
     *
     * @param FilesystemLoader $loader
     * @param Environment $twig
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
     * Appelle une méthode du contrôleur fournie en paramètre
     *
     * @param string $method
     * @param array|null $args
     * @return mixed
     * @throws MethodNotFoundException
     */
    public function call(string $method, ?array $args): mixed
    {
        if (!method_exists($this, $method)) {
            throw new MethodNotFoundException('La méthode ' . $method . ' n\'existe pas dans le contrôleur ' . get_class($this));
        }
        return $this->{$method}(...array_values($args));
    }

    /**
     * Retourne la connexion à la base de données
     *
     * @return PDO
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * Modifie la connexion à la base de données
     *
     * @param PDO $pdo
     * @return void
     */
    public function setPdo(PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    /**
     * Retourne le loader de Twig
     *
     * @return FilesystemLoader
     */
    public function getLoader(): FilesystemLoader
    {
        return $this->loader;
    }

    /**
     * Modifie le loader de Twig
     *
     * @param FilesystemLoader $loader
     * @return void
     */
    public function setLoader(FilesystemLoader $loader): void
    {
        $this->loader = $loader;
    }

    /**
     * Retourne l'environnement de Twig
     *
     * @return Environment
     */
    public function getTwig(): Environment
    {
        return $this->twig;
    }

    /**
     * Modifie l'environnement de Twig
     *
     * @param Environment $twig
     * @return void
     */
    public function setTwig(Environment $twig): void
    {
        $this->twig = $twig;
    }

    /**
     * Retourne les données GET
     *
     * @return array|null
     */
    public function getGet(): ?array
    {
        return $this->get;
    }

    /**
     * Modifie les données GET
     *
     * @param array|null $get
     * @return void
     */
    public function setGet(?array $get): void
    {
        $this->get = $get;
    }

    /**
     * Retourne les données POST
     *
     * @return array|null
     */
    public function getPost(): ?array
    {
        return $this->post;
    }

    /**
     * Modifie les données POST
     *
     * @param array|null $post
     * @return void
     */
    public function setPost(?array $post): void
    {
        $this->post = $post;
    }
}