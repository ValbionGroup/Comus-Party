<?php
/**
 * @brief  Le fichier contient la déclaration & définition de la classe Cookie.
 *
 * @file Cookie.php
 * @author Lucas ESPIET "espiet.l@valbion.com"
 * @version 1.0
 * @date 2025-02-26
 */

namespace ComusParty\App;

/**
 * @brief Class Cookie
 * @details La classe Cookie permet de gérer les cookies de l'application
 */
class Cookie
{
    /**
     * @brief Crée un cookie ou met à jour un cookie existant
     * @param string $name Nom du cookie
     * @param string $value Valeur du cookie
     * @param int|null $expire Date d'expiration du cookie
     * @return void
     */
    public static function set(string $name, string $value, ?int $expire = null): void
    {
        setcookie($name, $value, $expire ?? time() + 60 * 60 * 24 * 30, BASE_URI, parse_url(BASE_URL, PHP_URL_HOST), true, true);
    }

    /**
     * @brief Récupère la valeur d'un cookie
     * @param string $name Nom du cookie
     * @return string|null Valeur du cookie
     */
    public static function get(string $name): string|null
    {
        return $_COOKIE[$name] ?? null;
    }

    /**
     * @brief Supprime un cookie
     * @param string $name Nom du cookie
     */
    public static function delete(string $name): void
    {
        setcookie($name, '', time() - 3600);
    }
}