<?php

/**
 * @brief Fichier de constantes des mails
 *
 * @file mail.php
 * @author Enzo HAMID "ehamid@iutbayonne.univ-pau.fr"
 * @version 1.0
 * @date 2024-11-27
 */




 /**
 * @brief Hôte du serveur SMTP
 */
 define('MAIL_HOST', $_ENV['MAIL_HOST']);


/**
 * @brief Port du serveur SMTP
 */
 define('MAIL_PORT', $_ENV['MAIL_PORT']);


/**
 * @brief Protocole de communication
 */
 define('MAIL_SECURITY', $_ENV['MAIL_SECURITY']);

 
 /**
  * @brief Nom d'utilisateur de connexion
  */
 define('MAIL_USER', $_ENV['MAIL_USER']);

 
 /**
  * @brief Mot de passe de connexion
  */
 define('MAIL_PASS', $_ENV['MAIL_PASS']);

 
 /**
  * @brief Mail de l'expediteur
  */
 define('MAIL_FROM', $_ENV['MAIL_FROM']);

 
/**
 * @brief TODO
 */
 define('MAIL_BASE', $_ENV['MAIL_BASE']);


