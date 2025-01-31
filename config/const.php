<?php
/**
 * @brief Fichier de constantes
 *
 * @file const.php
 * @author Lucas ESPIET "lespiet@iutbayonne.univ-pau.fr"
 * @version 1.0
 * @date 2024-11-05
 */

namespace ComusParty;

/**
 * @brief Emplacement des fichiers de jeux
 */
define('GAMES_LOCATION', 'games/');

/**
 * @brief URL complète de l'application
 */
define('BASE_URL', $_ENV['BASE_URL']);

/**
 * @brief URI de base de l'application
 */
define('BASE_URI', $_ENV['BASE_URI']);


/**
 * @brief Système de sauvegarde actif ou non
 */
define('BACKUP_ENABLE', $_ENV['BACKUP_ENABLE']);

/**
 * @brief Mode de sauvegarde
 * @details Mode de sauvegarde actif :
 *  - cron : Sauvegarde automatique via tâche cron
 *  - manual : Sauvegarde lors du chargement du contrôleur principal
 */
define('BACKUP_MODE', $_ENV['BACKUP_TYPE']);

/**
 * @brief Nombre de sauvegardes à conserver
 */
define('BACKUP_RETENTION', $_ENV['BACKUP_RETENTION']);

/**
 * @brief Nombre de lignes à récupérer en une seule requête
 */
define('BACKUP_FETCH_LIMIT', $_ENV['BACKUP_FETCH_LIMIT']);

/**
 * @brief Interval de sauvegarde automatique (en minutes)
 */
define('BACKUP_INTERVAL', $_ENV['BACKUP_INTERVAL']);