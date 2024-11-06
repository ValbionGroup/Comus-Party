<?php

namespace models;

/**
 *
 */
class Router
{
    protected array $routes = [];
    private static ?Router $instance = null;

    /**
     * Permet d'ajouter une route au tableau de routes du Router
     *
     * @param string $method
     * @param string $url
     * @param callable $target
     * @return void
     */
    private function addRoute(string $method, string $url, callable $target): void
    {
        $this->routes[$method][BASE_URL.$url] = $target;
    }

    /**
     * Ajout d'une route GET
     *
     * @param string $url URL demandée
     * @param callable $target Action à effectuer
     * @return void
     */
    public function get(string $url, callable $target): void
    {
        $this->addRoute('GET', $url, $target);
    }

    /**
     * Ajout d'une route POST
     *
     * @param string $url URL demandée
     * @param callable $target Action à effectuer
     * @return void
     */
    public function post(string $url, callable $target): void
    {
        $this->addRoute('POST', $url, $target);
    }

    /**
     * Ajout d'une route PUT
     *
     * @param string $url URL demandée
     * @param callable $target Action à effectuer
     * @return void
     */
    public function put(string $url, callable $target): void
    {
        $this->addRoute('PUT', $url, $target);
    }

    /**
     * Ajout d'une route DELETE
     *
     * @param string $url URL demandée
     * @param callable $target Action à effectuer
     * @return void
     */
    public function delete(string $url, callable $target): void
    {
        $this->addRoute('DELETE', $url, $target);
    }


    /**
     * Permet de vérifier si la route demandée existe.
     * Si oui, on effectue ce qui a été défini pour cette route.
     * Sinon, on jette une RouteNotFoundException
     *
     * @throws RouteNotFoundException
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

        throw new RouteNotFoundException('Route '.$url.' ('.$method.')'.' not found', 404);
    }

    public static function getInstance(): Router
    {
        if (is_null(self::$instance)) {
            self::$instance = new Router();
        }
        return self::$instance;
    }

    private function __construct() {}

    private function __clone() {}

    private function __wakeup() {}
}