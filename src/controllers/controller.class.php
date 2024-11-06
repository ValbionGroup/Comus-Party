<?php

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
     * @var \Twig\Loader\FilesystemLoader
     */
    private \Twig\Loader\FilesystemLoader $loader;

    /**
     * L'environnement de Twig
     *
     * @var \Twig\Environment
     */

    private \Twig\Environment $twig;
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
     * @param \Twig\Loader\FilesystemLoader $loader
     * @param \Twig\Environment $twig
     */
    public function __construct(\Twig\Loader\FilesystemLoader $loader, \Twig\Environment $twig) {
        $db = Db::getInstance();
        $this->pdo = Db::getInstance()->getConnection();
        $this->loader = $loader;
        $this->twig = $twig;

        if (isset($_GET) && !empty($_GET)) {
            $this->get = $_GET;
        }

        if (isset($_POST) && !empty($_POST)) {
            $this->post = $_POST;
        }
    }

    /**
     * Appelle une méthode du contrôleur fournie en paramètre
     *
     * @param string $method
     * @return mixed
     */
    public function call(string $method) : mixed {
        if (!method_exists($this, $method)) {
            return $this->error('Method not found in controller'. __CLASS__);
        }
        return $this->$method();
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
     * @return \Twig\Loader\FilesystemLoader
     */
    public function getLoader(): \Twig\Loader\FilesystemLoader
    {
        return $this->loader;
    }

    /**
     * Modifie le loader de Twig
     *
     * @param \Twig\Loader\FilesystemLoader $loader
     * @return void
     */
    public function setLoader(\Twig\Loader\FilesystemLoader $loader): void
    {
        $this->loader = $loader;
    }

    /**
     * Retourne l'environnement de Twig
     *
     * @return \Twig\Environment
     */
    public function getTwig(): \Twig\Environment
    {
        return $this->twig;
    }

    /**
     * Modifie l'environnement de Twig
     *
     * @param \Twig\Environment $twig
     * @return void
     */
    public function setTwig(\Twig\Environment $twig): void
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