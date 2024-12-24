<?php
/**
 * @brief Fichier de la classe Router
 * @file Router.php
 * @author Lucas ESPIET "lespiet@iutbayonne.univ-pau.fr"
 * @version 1.2
 * @date 2024-12-18
 */

namespace ComusParty\App;

use ComusParty\App\Exception\RouteNotFoundException;
use ComusParty\App\Exception\UnauthorizedAccessException;
use Exception;

/**
 * @brief Classe Router permettant de gérer les routes
 * @details La classe Router permet de gérer les routes de l'application.
 *  Elle stocke les routes, permet de les ajouter et enfin de les appeler et de vérifier si elles existent.
 */
class Router
{
    /**
     * @brief Instance du Router
     * @var Router|null Instance du Router
     */
    private static ?Router $instance = null;
    /**
     * @brief Tableau des routes
     * @var array Tableau des routes
     */
    protected array $routes = [];

    /**
     * @brief Constructeur de la classe Router
     * @details Constructeur privé pour empêcher l'instanciation de la classe
     */
    private function __construct()
    {
    }

    /**
     * @brief Permet de récupérer l'instance du Router
     * @return Router Instance du Router
     */
    public static function getInstance(): Router
    {
        if (is_null(self::$instance)) {
            self::$instance = new Router();
        }
        return self::$instance;
    }

    /**
     * @brief Ajout d'une route GET
     * @param string $url URL demandée
     * @param callable $target Action à effectuer
     * @param string|null $middleware Middleware à utiliser
     * @return void
     */
    public function get(string $url, callable $target, ?string $middleware = null): void
    {
        $this->addRoute('GET', $url, $target, $middleware);
    }

    /**
     * @brief Permet d'ajouter une route au tableau de routes du Router
     * @param string $method Méthode HTTP (GET, POST, PUT, DELETE)
     * @param string $url URL demandée
     * @param callable $target Action à effectuer
     * @param string|null $middleware Middleware à utiliser
     * @return void
     */
    private function addRoute(string $method, string $url, callable $target, ?string $middleware = null): void
    {
        if (is_null($middleware)) {
            $middleware = '*';
        }

        $this->routes[$method][$middleware][BASE_URI . $url] = $target;
    }

    /**
     * @brief Ajout d'une route POST
     * @param string $url URL demandée
     * @param callable $target Action à effectuer
     * @param string|null $middleware Middleware à utiliser
     * @return void
     */
    public function post(string $url, callable $target, ?string $middleware = null): void
    {
        $this->addRoute('POST', $url, $target, $middleware);
    }

    /**
     * @brief Ajout d'une route PUT
     * @param string $url URL demandée
     * @param callable $target Action à effectuer
     * @param string|null $middleware Middleware à utiliser
     * @return void
     */
    public function put(string $url, callable $target, ?string $middleware = null): void
    {
        $this->addRoute('PUT', $url, $target, $middleware);
    }

    /**
     * @brief Ajout d'une route DELETE
     * @param string $url URL demandée
     * @param callable $target Action à effectuer
     * @param string|null $middleware Middleware à utiliser
     * @return void
     */
    public function delete(string $url, callable $target, ?string $middleware = null): void
    {
        $this->addRoute('DELETE', $url, $target, $middleware);
    }

    /**
     * @brief Permet d'accéder à la route demandée
     * @details Permet de vérifier si la route demandée existe.
     *  Si oui, on effectue ce qui a été défini pour cette route.
     *  Sinon, on lève une RouteNotFoundException
     *
     * La gestion des Middlewares permet les cas suivant :
     *  - Si l'utilisateur n'est pas connecté, il ne peut accéder qu'aux routes définies pour les invités (guest)
     *  - Si l'utilisateur est connecté, il ne peut accéder qu'aux routes définies pour son rôle
     *  - Si une route est définie pour tous les rôles (*), elle est accessible par tous les utilisateurs (connectés ou non)
     *  - Si une route est définie pour tous les rôles mais nécessite une connexion (auth), elle est accessible par tous les utilisateurs connectés
     *
     * Si aucune route ne correspond à la demande mais existe pour un autre rôle, on lève une UnauthorizedAccessException
     *
     * @throws RouteNotFoundException Dans le cas où la route demandée n'existe pas
     * @throws UnauthorizedAccessException Dans le cas où l'utilisateur n'a pas les droits pour accéder à la route
     * @throws Exception Dans le cas où une autre exception est levée
     * @return void
     */
    public function matchRoute(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = $_SERVER['REQUEST_URI'];
        $role = $_SESSION['role'] ?? null;

        if (isset($this->routes[$method])) {
            if (is_null($role)) {
                if ($this->checkRouteAndCall('guest', $method, $url)) return;
                if ($this->checkRouteAndCall('*', $method, $url)) return;

                // Redirection vers la page de connexion si l'utilisateur n'est pas connecté
                header('Location: /login');
                exit;
            } else {
                if ($this->checkRouteAndCall($role, $method, $url)) return;
                if ($this->checkRouteAndCall('*', $method, $url)) return;
                if ($this->checkRouteAndCall('auth', $method, $url)) return;
            }

            foreach ($this->routes[$method] as $target) {
                foreach (array_keys($target) as $routeUrl) {
                    $pattern = preg_replace('/\/:([^\/]+)/', '/(?P<$1>[^/]+)', $routeUrl);
                    if (preg_match('#^' . $pattern . '$#', $url)) {
                        throw new UnauthorizedAccessException('Unauthorized access to route ' . $url . ' (' . $method . ')');
                    }
                }
            }
        }

        throw new RouteNotFoundException('Route ' . $url . ' (' . $method . ')' . ' not found');
    }

    /**
     * @brief Permet de vérifier si une route existe
     * @param string $middleware Middleware à vérifier
     * @param string $method Méthode HTTP (GET, POST, PUT, DELETE)
     * @param string $url URL demandée
     * @return bool Retourne true si la fonction a été appelée, false sinon
     */
    private function checkRouteAndCall(string $middleware, string $method, string $url): bool
    {
        if (isset($this->routes[$method][$middleware])) {
            foreach ($this->routes[$method][$middleware] as $routeUrl => $target) {
                if ($this->callFunctionFromRoute($routeUrl, $target, $url)) return true;
            }
        }
        return false;
    }

    /**
     * @brief Permet d'appeler la fonction associée à la route
     * @param string $routeUrl URL de la route
     * @param callable $target Fonction à appeler
     * @param string $url URL demandée par l'utilisateur
     * @return bool Retourne true si la fonction a été appelée, false sinon
     */
    private function callFunctionFromRoute(string $routeUrl, callable $target, string $url): bool
    {
        $pattern = preg_replace('/\/:([^\/]+)/', '/(?P<$1>[^/]+)', $routeUrl);
        if (preg_match('#^' . $pattern . '$#', $url, $matches)) {
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            call_user_func_array($target, $params);
            return true;
        }
        return false;
    }

    /**
     * @brief Empêche la désérialisation de l'instance
     * @return void
     * @throws Exception Dans le cas où la désérialisation est tentée
     */
    public function __wakeup(): void
    {
        throw new Exception("Cannot unserialize a singleton.");
    }

    /**
     * @brief Empêche le clonage de l'instance
     * @return void
     */
    private function __clone(): void
    {
    }
}