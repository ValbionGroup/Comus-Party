<?php

/**
 * @brief Fichier de constantes de la base de données
 *
 * @file db.php
 * @author Lucas ESPIET "lespiet@iutbayonne.univ-pau.fr"
 * @version 1.0
 * @date 2024-11-05
 */


/**
 * @brief Type de base de données
 */
define('DB_TYPE', $_ENV['DB_TYPE']);


/**
 * @brief Hôte de la base de données
 */
define('DB_HOST', $_ENV['DB_HOST']);


/**
 * @brief Port de la base de données
 */
define('DB_PORT', $_ENV['DB_PORT']);


/**
 * @brief Nom de la base de données
 */
define('DB_NAME', $_ENV['DB_NAME']);


/**
 * @brief Utilisateur de la base de données
 */
define('DB_USER', $_ENV['DB_USER']);


/**
 * @brief Mot de passe de la base de données
 */
define('DB_PASS', $_ENV['DB_PASS']);


/**
 * @brief Préfixe des tables de la base de données
 */
define('DB_PREFIX', $_ENV['DB_PREFIX']);