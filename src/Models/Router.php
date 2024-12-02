<?php

/**
 * @brief Fichier de la classe Router
 *
 * @file Router.php
 * @author Lucas ESPIET "lespiet@iutbayonne.univ-pau.fr"
 * @version 1.0
 * @date 2024-11-12
 */

namespace ComusParty\Models;

use ComusParty\Models\Exception\RouteNotFoundException;
use Exception;

/**
 * @brief Classe Router permettant de gérer les routes
 * @details La classe Router permet de gérer les routes de l'application.
 *  Elle stocke les routes, permet de les ajouter et enfin de les appeler et de vérifier si elles existent.
 */
class Router
{
    /**
     * @var array Tableau des routes
     */
    protected array $routes = [];

    /**
     * @var Router|null Instance du Router
     */
    private static ?Router $instance = null;

    /**
     * @brief Permet d'ajouter une route au tableau de routes du Router
     * @param string $method Méthode HTTP (GET, POST, PUT, DELETE)
     * @param string $url URL demandée
     * @param callable $target Action à effectuer
     * @return void
     */
    private function addRoute(string $method, string $url, callable $target): void
    {
        $this->routes[$method][BASE_URI.$url] = $target;
    }

    /**
     * @brief Ajout d'une route GET
     * @param string $url URL demandée
     * @param callable $target Action à effectuer
     * @return void
     */
    public function get(string $url, callable $target): void
    {
        $this->addRoute('GET', $url, $target);
    }

    /**
     * @brief Ajout d'une route POST
     * @param string $url URL demandée
     * @param callable $target Action à effectuer
     * @return void
     */
    public function post(string $url, callable $target): void
    {
        $this->addRoute('POST', $url, $target);
    }

    /**
     * @brief Ajout d'une route PUT
     * @param string $url URL demandée
     * @param callable $target Action à effectuer
     * @return void
     */
    public function put(string $url, callable $target): void
    {
        $this->addRoute('PUT', $url, $target);
    }

    /**
     * @brief Ajout d'une route DELETE
     * @param string $url URL demandée
     * @param callable $target Action à effectuer
     * @return void
     */
    public function delete(string $url, callable $target): void
    {
        $this->addRoute('DELETE', $url, $target);
    }


    /**
     * @brief Permet d'accéder à la route demandée
     * @details Permet de vérifier si la route demandée existe.
     *  Si oui, on effectue ce qui a été défini pour cette route.
     *  Sinon, on lève une RouteNotFoundException
     *
     * @throws RouteNotFoundException Dans le cas où la route demandée n'existe pas
     * @throws Exception Dans le cas où une autre exception est levée
     * @return void
     */
    public function matchRoute(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = $_SERVER['REQUEST_URI'];

        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $routeUrl => $target) {

                $pattern = preg_replace('/\/:([^\/]+)/', '/(?P<$1>[^/]+)', $routeUrl);
                if (preg_match('#^' . $pattern . '$#', $url, $matches)) {
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                    call_user_func_array($target, $params);
                    return;
                }
            }
        }

        throw new RouteNotFoundException('Route '.$url.' ('.$method.')'.' not found');
    }

    /**
     * @brief Permet de récupérer l'instance du Router
     * @return Router
     */
    public static function getInstance(): Router
    {
        if (is_null(self::$instance)) {
            self::$instance = new Router();
        }
        return self::$instance;
    }

    /**
     * @brief Constructeur de la classe Router
     * @details Constructeur privé pour empêcher l'instanciation de la classe
     */
    private function __construct() {}

    /**
     * @brief Empêche le clonage de l'instance
     * @return void
     */
    private function __clone(): void {}

    /**
     * @brief Empêche la désérialisation de l'instance
     * @return void
     */
    public function __wakeup(): void {
        throw new Exception("Cannot unserialize a singleton.");
    }
}