<?php

/**
 * @brief Fichier de constantes pour les mails
 *
 * @file mail.php
 * @author Lucas ESPIET "lespiet@iutbayonne.univ-pau.fr"
 * @version 1.0
 * @date 2024-12-09
 */


/**
 * @brief Hôte du serveur mail
 */
define('MAIL_HOST', $_ENV['MAIL_HOST']);

/**
 * @brief Port du serveur mail
 */
define('MAIL_PORT', $_ENV['MAIL_PORT']);

/**
 * @brief Protocole de sécurité du serveur mail
 */
define('MAIL_SECURITY', $_ENV['MAIL_SECURITY']);

/**
 * @brief Nom d'utilisateur du compte du serveur mail
 */
define('MAIL_USER', $_ENV['MAIL_USER']);

/**
 * @brief Mot de passe du compte du serveur mail
 */
define('MAIL_PASS', $_ENV['MAIL_PASS']);

/**
 * @brief Adresse email d'expédition des mails
 */
define('MAIL_FROM', $_ENV['MAIL_FROM']);

/**
 * @brief Contenu à ajouter à la fin du sujet de mail
 */
define('MAIL_BASE', $_ENV['MAIL_BASE']);