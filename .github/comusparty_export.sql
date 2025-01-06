-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 17 déc. 2024 à 08:09
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `comusparty`
--

-- --------------------------------------------------------

--
-- Structure de la table `cp_achieve`
--

CREATE TABLE `cp_achieve`
(
    `success_id`  bigint(20)  NOT NULL,
    `player_uuid` varchar(63) NOT NULL,
    `date`        datetime    NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

--
-- Déchargement des données de la table `cp_achieve`
--

INSERT INTO `cp_achieve` (`success_id`, `player_uuid`, `date`)
VALUES (1, 'uuid1', '2024-11-13 16:18:39'),
       (2, 'uuid2', '2024-11-13 16:18:39'),
       (3, 'uuid3', '2024-11-14 09:15:01'),
       (4, 'uuid4', '2024-11-14 09:15:01');

-- --------------------------------------------------------

--
-- Structure de la table `cp_article`
--

CREATE TABLE `cp_article`
(
    `id`          bigint(20)                   NOT NULL,
    `name`        varchar(255)                 NOT NULL,
    `description` text                         NOT NULL,
    `type`        enum ('banner','pfp','text') NOT NULL,
    `file_path`   varchar(255)                          DEFAULT NULL,
    `price_point` int(11)                               DEFAULT NULL,
    `price_euro`  decimal(7, 2)                         DEFAULT NULL,
    `created_at`  timestamp                    NOT NULL DEFAULT current_timestamp(),
    `updated_at`  timestamp                    NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

--
-- Déchargement des données de la table `cp_article`
--

INSERT INTO `cp_article` (`id`, `name`, `description`, `type`, `file_path`, `price_point`, `price_euro`, `created_at`,
                          `updated_at`)
VALUES (1, 'Banner One', 'Description for banner one', 'banner', '/img/banner1.png', 100, 2.99, '2024-11-13 15:18:39',
        '2024-11-13 15:18:39'),
       (2, 'Profile Pic One', 'Description for profile picture one', 'pfp', '/img/pfp1.png', 200, 4.99,
        '2024-11-13 15:18:39', '2024-11-13 15:18:39'),
       (3, 'Banner Two', 'Description for banner two', 'banner', '/img/banner2.png', 150, 3.99, '2024-11-14 08:15:01',
        '2024-11-14 08:15:01'),
       (4, 'Profile Pic Two', 'Description for profile picture two', 'pfp', '/img/pfp2.png', 250, 5.99,
        '2024-11-14 08:15:01', '2024-11-14 08:15:01');

-- --------------------------------------------------------

--
-- Structure de la table `cp_game`
--

CREATE TABLE `cp_game`
(
    `id`          bigint(20)                                     NOT NULL,
    `name`        varchar(255)                                   NOT NULL,
    `description` text                                                    DEFAULT NULL,
    `img_path`    varchar(255)                                            DEFAULT NULL,
    `state`       enum ('available','unavailable','maintenance') NOT NULL,
    `created_at`  timestamp                                      NOT NULL DEFAULT current_timestamp(),
    `updated_at`  timestamp                                      NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

--
-- Déchargement des données de la table `cp_game`
--

INSERT INTO `cp_game` (`id`, `name`, `description`, `img_path`, `state`, `created_at`, `updated_at`)
VALUES (1, 'Game One', 'First game description', '/img/game1.png', 'available', '2024-11-13 15:18:39',
        '2024-11-13 15:18:39'),
       (2, 'Game Two', 'Second game description', '/img/game2.png', 'maintenance', '2024-11-13 15:18:39',
        '2024-11-13 15:18:39'),
       (3, 'Game Three', 'Third game description', '/img/game3.png', 'available', '2024-11-14 08:15:01',
        '2024-11-14 08:15:01'),
       (4, 'Game Four', 'Fourth game description', '/img/game4.png', 'maintenance', '2024-11-14 08:15:01',
        '2024-11-14 08:15:01');

-- --------------------------------------------------------

--
-- Structure de la table `cp_game_record`
--

CREATE TABLE `cp_game_record`
(
    `uuid`       varchar(63)                           NOT NULL,
    `game_id`    bigint(20)                            NOT NULL,
    `hosted_by`  varchar(63)                           NOT NULL,
    `state`      enum ('waiting','started','finished') NOT NULL DEFAULT 'waiting',
    `created_at` timestamp                             NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp                             NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

--
-- Déchargement des données de la table `cp_game_record`
--

INSERT INTO `cp_game_record` (`uuid`, `game_id`, `hosted_by`, `state`, `created_at`, `updated_at`)
VALUES ('game_rec_uuid1', 1, 'uuid1', 'started', '2024-11-13 15:18:39', '2024-11-13 15:18:39'),
       ('game_rec_uuid2', 2, 'uuid2', 'waiting', '2024-11-13 15:18:39', '2024-11-13 15:18:39'),
       ('game_rec_uuid3', 3, 'uuid3', '', '2024-11-14 08:15:01', '2024-11-14 08:15:01'),
       ('game_rec_uuid4', 4, 'uuid4', 'waiting', '2024-11-14 08:15:01', '2024-11-14 08:15:01');

-- --------------------------------------------------------

--
-- Structure de la table `cp_invoice`
--

CREATE TABLE `cp_invoice`
(
    `id`           bigint(20)                     NOT NULL,
    `player_uuid`  varchar(63)                    NOT NULL,
    `payment_type` enum ('card','paypal','coins') NOT NULL,
    `created_at`   timestamp                      NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

--
-- Déchargement des données de la table `cp_invoice`
--

INSERT INTO `cp_invoice` (`id`, `player_uuid`, `payment_type`, `created_at`)
VALUES (1, 'uuid1', 'card', '2024-11-13 15:18:39'),
       (2, 'uuid2', 'paypal', '2024-11-13 15:18:39'),
       (3, 'uuid3', 'card', '2024-11-14 08:15:01'),
       (4, 'uuid4', 'paypal', '2024-11-14 08:15:01');

-- --------------------------------------------------------

--
-- Structure de la table `cp_invoice_row`
--

CREATE TABLE `cp_invoice_row`
(
    `article_id` bigint(20) NOT NULL,
    `invoice_id` bigint(20) NOT NULL,
    `active`     tinyint(1) NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

--
-- Déchargement des données de la table `cp_invoice_row`
--

INSERT INTO `cp_invoice_row` (`article_id`, `invoice_id`, `active`)
VALUES (1, 1, 1),
       (1, 3, 0),
       (2, 2, 1),
       (2, 4, 0);

-- --------------------------------------------------------

--
-- Structure de la table `cp_moderator`
--

CREATE TABLE `cp_moderator`
(
    `uuid`       varchar(63) NOT NULL,
    `user_id`    bigint(20)  NOT NULL,
    `first_name` varchar(60) NOT NULL,
    `last_name`  varchar(60) NOT NULL,
    `created_at` timestamp   NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp   NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

--
-- Déchargement des données de la table `cp_moderator`
--

INSERT INTO `cp_moderator` (`uuid`, `user_id`, `first_name`, `last_name`, `created_at`, `updated_at`)
VALUES ('mod_uuid1', 1, 'John', 'Doe', '2024-11-13 15:18:39', '2024-11-13 15:18:39'),
       ('mod_uuid2', 2, 'Jane', 'Doe', '2024-11-13 15:18:39', '2024-11-13 15:18:39'),
       ('mod_uuid3', 3, 'Alex', 'Smith', '2024-11-14 08:15:01', '2024-11-14 08:15:01'),
       ('mod_uuid4', 4, 'Lisa', 'Wong', '2024-11-14 08:15:01', '2024-11-14 08:15:01');

-- --------------------------------------------------------

--
-- Structure de la table `cp_penalty`
--

CREATE TABLE `cp_penalty`
(
    `id`             bigint(20)              NOT NULL,
    `created_by`     varchar(63)             NOT NULL,
    `cancelled_by`   varchar(63)                      DEFAULT NULL,
    `penalized_uuid` varchar(63)             NOT NULL,
    `reason`         text                             DEFAULT NULL,
    `duration`       int(11)                          DEFAULT 0,
    `type`           enum ('muted','banned') NOT NULL,
    `cancelled_at`   datetime                         DEFAULT NULL,
    `created_at`     timestamp               NOT NULL DEFAULT current_timestamp(),
    `updated_at`     timestamp               NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

--
-- Déchargement des données de la table `cp_penalty`
--

INSERT INTO `cp_penalty` (`id`, `created_by`, `cancelled_by`, `penalized_uuid`, `reason`, `duration`, `type`,
                          `cancelled_at`, `created_at`, `updated_at`)
VALUES (1, 'mod_uuid1', NULL, 'uuid2', 'Inappropriate behavior', 30, 'muted', NULL, '2024-11-13 15:18:39',
        '2024-11-13 15:18:39'),
       (2, 'mod_uuid2', NULL, 'uuid3', 'Spamming', 15, 'banned', NULL, '2024-11-14 08:15:01', '2024-11-14 08:15:01');

-- --------------------------------------------------------

--
-- Structure de la table `cp_played`
--

CREATE TABLE `cp_played`
(
    `game_uuid`   varchar(63) NOT NULL,
    `player_uuid` varchar(63) NOT NULL,
    `token`       varchar(16) NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

--
-- Déchargement des données de la table `cp_played`
--

INSERT INTO `cp_played` (`game_uuid`, `player_uuid`)
VALUES ('game_rec_uuid1', 'uuid1'),
       ('game_rec_uuid2', 'uuid2'),
       ('game_rec_uuid3', 'uuid3'),
       ('game_rec_uuid4', 'uuid4');

-- --------------------------------------------------------

--
-- Structure de la table `cp_player`
--

CREATE TABLE `cp_player`
(
    `uuid`       varchar(63)  NOT NULL,
    `user_id`    bigint(20)   NOT NULL,
    `username`   varchar(120) NOT NULL,
    `xp`         int(11)      NOT NULL DEFAULT 0,
    `elo`        int(11)      NOT NULL DEFAULT 0,
    `comus_coin` int(11)      NOT NULL DEFAULT 0,
    `created_at` timestamp    NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp    NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

--
-- Déchargement des données de la table `cp_player`
--

INSERT INTO `cp_player` (`uuid`, `user_id`, `username`, `xp`, `elo`, `comus_coin`, `created_at`, `updated_at`)
VALUES ('uuid1', 1, 'JohnDoe', 500, 1200, 100, '2024-11-13 15:18:39', '2024-11-13 15:18:39'),
       ('uuid2', 2, 'JaneDoe', 300, 1150, 50, '2024-11-13 15:18:39', '2024-11-13 15:18:39'),
       ('uuid3', 3, 'AlexSmith', 450, 1180, 75, '2024-11-14 08:15:01', '2024-11-14 08:15:01'),
       ('uuid4', 4, 'LisaWong', 600, 1250, 150, '2024-11-14 08:15:01', '2024-11-14 08:15:01');

-- --------------------------------------------------------

--
-- Structure de la table `cp_pswd_reset_token`
--

CREATE TABLE `cp_pswd_reset_token`
(
    `user_id`    bigint(20)  NOT NULL,
    `token`      varchar(60) NOT NULL,
    `created_at` timestamp   NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

--
-- Déchargement des données de la table `cp_pswd_reset_token`
--

INSERT INTO `cp_pswd_reset_token` (`user_id`, `token`, `created_at`)
VALUES (1, 'reset_token_1', '2024-11-13 15:18:39'),
       (2, 'reset_token_2', '2024-11-13 15:18:39'),
       (3, 'reset_token_3', '2024-11-14 08:15:02'),
       (4, 'reset_token_4', '2024-11-14 08:15:02');

-- --------------------------------------------------------

--
-- Structure de la table `cp_report`
--

CREATE TABLE `cp_report`
(
    `id`            bigint(20)                   NOT NULL,
    `object`        enum ('language','fairplay') NOT NULL,
    `description`   text                         NOT NULL,
    `treated`       tinyint(1)                   NOT NULL DEFAULT 0,
    `treated_by`    varchar(63)                           DEFAULT NULL,
    `reported_uuid` varchar(63)                  NOT NULL,
    `sender_uuid`   varchar(63)                  NOT NULL,
    `created_at`    timestamp                    NOT NULL DEFAULT current_timestamp(),
    `updated_at`    timestamp                    NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

--
-- Déchargement des données de la table `cp_report`
--

INSERT INTO `cp_report` (`id`, `object`, `description`, `treated`, `treated_by`, `reported_uuid`, `sender_uuid`,
                         `created_at`, `updated_at`)
VALUES (1, 'language', 'Inappropriate language used', 1, 'mod_uuid1', 'uuid1', 'uuid2', '2024-11-13 15:18:39',
        '2024-11-13 15:18:39'),
       (2, '', 'Cheating behavior observed', 0, NULL, 'uuid4', 'uuid3', '2024-11-14 08:15:02', '2024-11-14 08:15:02');

-- --------------------------------------------------------

--
-- Structure de la table `cp_success`
--

CREATE TABLE `cp_success`
(
    `id`          bigint(20)  NOT NULL,
    `depend_on`   bigint(20)           DEFAULT NULL,
    `name`        varchar(60) NOT NULL,
    `description` text                 DEFAULT NULL,
    `type`        varchar(10) NOT NULL,
    `prize`       varchar(10) NOT NULL,
    `created_at`  timestamp   NOT NULL DEFAULT current_timestamp(),
    `updated_at`  timestamp   NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

--
-- Déchargement des données de la table `cp_success`
--

INSERT INTO `cp_success` (`id`, `depend_on`, `name`, `description`, `type`, `prize`, `created_at`, `updated_at`)
VALUES (1, NULL, 'First Win', 'Win your first game', 'bronze', '100xp', '2024-11-13 15:18:39', '2024-11-13 15:18:39'),
       (2, 1, 'Champion', 'Win multiple games', 'gold', '500xp', '2024-11-13 15:18:39', '2024-11-13 15:18:39'),
       (3, 2, 'Veteran', 'Win 10 games', 'silver', '300xp', '2024-11-14 08:15:01', '2024-11-14 08:15:01'),
       (4, 3, 'Master', 'Achieve a high score', 'platinum', '1000xp', '2024-11-14 08:15:01', '2024-11-14 08:15:01');

-- --------------------------------------------------------

--
-- Structure de la table `cp_suggestion`
--

CREATE TABLE `cp_suggestion`
(
    `id`          int(11)                         NOT NULL,
    `object`      enum ('bug','jeu','ui','other') NOT NULL,
    `content`     text                            NOT NULL,
    `author_uuid` varchar(63)                     NOT NULL,
    `treated_by`  varchar(63)                              DEFAULT NULL,
    `accepted`    tinyint(1)                      NOT NULL DEFAULT 0,
    `created_at`  timestamp                       NOT NULL DEFAULT current_timestamp(),
    `treated_at`  timestamp                       NULL     DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

--
-- Déchargement des données de la table `cp_suggestion`
--

INSERT INTO `cp_suggestion` (`id`, `object`, `content`, `author_uuid`, `treated_by`, `accepted`, `created_at`,
                             `treated_at`)
VALUES (1, 'bug', 'Améliorer la performance des pages en optimisant les requêtes SQL.', 'uuid1', 'mod_uuid1', 0,
        '2024-12-17 07:30:00', '2024-12-18 08:30:00'),
       (2, 'ui', 'Ajouter un système de notifications pour les nouvelles suggestions traitées.', 'uuid2', 'mod_uuid2',
        1, '2024-12-17 08:00:00', '2024-12-17 09:00:00'),
       (3, 'other', 'Permettre l\'exportation des suggestions au format CSV.', 'uuid3', NULL, 0, '2024-12-17 10:15:00',
        NULL),
       (4, 'ui', 'Intégrer un module de filtrage pour trier les suggestions par statut.', 'uuid4', 'mod_uuid1', 1,
        '2024-12-17 12:45:00', '2024-12-17 13:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `cp_tag`
--

CREATE TABLE `cp_tag`
(
    `id`   bigint(20)  NOT NULL,
    `name` varchar(60) NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

--
-- Déchargement des données de la table `cp_tag`
--

INSERT INTO `cp_tag` (`id`, `name`)
VALUES (1, 'Action'),
       (2, 'Adventure'),
       (3, 'Puzzle'),
       (4, 'Strategy');

-- --------------------------------------------------------

--
-- Structure de la table `cp_tagged`
--

CREATE TABLE `cp_tagged`
(
    `tag_id`  bigint(20) NOT NULL,
    `game_id` bigint(20) NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

--
-- Déchargement des données de la table `cp_tagged`
--

INSERT INTO `cp_tagged` (`tag_id`, `game_id`)
VALUES (1, 1),
       (2, 2),
       (3, 3),
       (4, 4);

-- --------------------------------------------------------

--
-- Structure de la table `cp_user`
--

CREATE TABLE `cp_user`
(
    `id`                bigint(20)   NOT NULL,
    `email`             varchar(255) NOT NULL,
    `password`          varchar(255) NOT NULL,
    `email_verified_at` datetime              DEFAULT NULL,
    `email_verif_token` varchar(60)           DEFAULT NULL,
    `disabled`          tinyint(1)   NOT NULL DEFAULT 0,
    `created_at`        timestamp    NOT NULL DEFAULT current_timestamp(),
    `updated_at`        timestamp    NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

--
-- Déchargement des données de la table `cp_user`
--

INSERT INTO `cp_user` (`id`, `email`, `password`, `email_verified_at`, `email_verif_token`, `disabled`, `created_at`,
                       `updated_at`)
VALUES (1, 'john.doe@example.com', '$2y$10$pWjH9MFAmeO8quFTZWSube4mx1KUiYiNXgIf0z9LrOA5TafaAWEw.',
        '2024-11-13 16:18:39', 'token1', 0, '2024-11-13 15:18:39', '2024-11-13 15:19:34'),
       (2, 'jane.doe@example.com', '$2y$10$Ms.jZ7V7Su0oC7o5PK3uOuT7rmp7al21xOF2gUHd/a/Ozq14/trHi',
        '2024-11-13 16:18:39', 'token2', 0, '2024-11-13 15:18:39', '2024-11-13 15:19:51'),
       (3, 'alex.smith@example.com', 'hashed_password3', '2024-11-14 09:15:01', 'token3', 0, '2024-11-14 08:15:01',
        '2024-11-14 08:15:01'),
       (4, 'lisa.wong@example.com', 'hashed_password4', '2024-11-14 09:15:01', 'token4', 0, '2024-11-14 08:15:01',
        '2024-11-14 08:15:01');

-- --------------------------------------------------------

--
-- Structure de la table `cp_won`
--

CREATE TABLE `cp_won`
(
    `game_uuid`   varchar(63) NOT NULL,
    `player_uuid` varchar(63) NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;

--
-- Déchargement des données de la table `cp_won`
--

INSERT INTO `cp_won` (`game_uuid`, `player_uuid`)
VALUES ('game_rec_uuid1', 'uuid1'),
       ('game_rec_uuid2', 'uuid2'),
       ('game_rec_uuid3', 'uuid3'),
       ('game_rec_uuid4', 'uuid4');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cp_achieve`
--
ALTER TABLE `cp_achieve`
    ADD PRIMARY KEY (`date`, `success_id`, `player_uuid`),
    ADD KEY `fk_achieve_success_id` (`success_id`),
    ADD KEY `fk_achieve_player_uuid` (`player_uuid`);

--
-- Index pour la table `cp_article`
--
ALTER TABLE `cp_article`
    ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cp_game`
--
ALTER TABLE `cp_game`
    ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cp_game_record`
--
ALTER TABLE `cp_game_record`
    ADD PRIMARY KEY (`uuid`),
    ADD KEY `fk_game_record_game_id` (`game_id`),
    ADD KEY `fk_game_record_hosted_uuid` (`hosted_by`);

--
-- Index pour la table `cp_invoice`
--
ALTER TABLE `cp_invoice`
    ADD PRIMARY KEY (`id`),
    ADD KEY `player_uuid` (`player_uuid`);

--
-- Index pour la table `cp_invoice_row`
--
ALTER TABLE `cp_invoice_row`
    ADD PRIMARY KEY (`article_id`, `invoice_id`),
    ADD KEY `fk_invoice_row_invoice_id` (`invoice_id`);

--
-- Index pour la table `cp_moderator`
--
ALTER TABLE `cp_moderator`
    ADD PRIMARY KEY (`uuid`),
    ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `cp_penalty`
--
ALTER TABLE `cp_penalty`
    ADD PRIMARY KEY (`id`),
    ADD KEY `fk_penalty_moderator_creation_uuid` (`created_by`),
    ADD KEY `fk_penalty_moderator_cancelled_uuid` (`cancelled_by`),
    ADD KEY `fk_penalty_player_uuid` (`penalized_uuid`);

--
-- Index pour la table `cp_played`
--
ALTER TABLE `cp_played`
    ADD PRIMARY KEY (`game_uuid`, `player_uuid`),
    ADD KEY `fk_played_player_uuid` (`player_uuid`);

--
-- Index pour la table `cp_player`
--
ALTER TABLE `cp_player`
    ADD PRIMARY KEY (`uuid`),
    ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `cp_pswd_reset_token`
--
ALTER TABLE `cp_pswd_reset_token`
    ADD PRIMARY KEY (`user_id`),
    ADD KEY `token` (`token`);

--
-- Index pour la table `cp_report`
--
ALTER TABLE `cp_report`
    ADD PRIMARY KEY (`id`),
    ADD KEY `fk_report_moderator_uuid` (`treated_by`),
    ADD KEY `fk_report_player_reported` (`reported_uuid`),
    ADD KEY `fk_report_player_sender` (`sender_uuid`);

--
-- Index pour la table `cp_success`
--
ALTER TABLE `cp_success`
    ADD PRIMARY KEY (`id`),
    ADD KEY `fk_success_success_dependent` (`depend_on`);

--
-- Index pour la table `cp_suggestion`
--
ALTER TABLE `cp_suggestion`
    ADD PRIMARY KEY (`id`),
    ADD KEY `fk_author_uuid` (`author_uuid`),
    ADD KEY `fk_treated_by` (`treated_by`);

--
-- Index pour la table `cp_tag`
--
ALTER TABLE `cp_tag`
    ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cp_tagged`
--
ALTER TABLE `cp_tagged`
    ADD PRIMARY KEY (`tag_id`, `game_id`),
    ADD KEY `fk_tagged_game_id` (`game_id`);

--
-- Index pour la table `cp_user`
--
ALTER TABLE `cp_user`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `cp_won`
--
ALTER TABLE `cp_won`
    ADD PRIMARY KEY (`game_uuid`, `player_uuid`),
    ADD KEY `fk_winned_player_uuid` (`player_uuid`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `cp_article`
--
ALTER TABLE `cp_article`
    MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 5;

--
-- AUTO_INCREMENT pour la table `cp_game`
--
ALTER TABLE `cp_game`
    MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 5;

--
-- AUTO_INCREMENT pour la table `cp_invoice`
--
ALTER TABLE `cp_invoice`
    MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 5;

--
-- AUTO_INCREMENT pour la table `cp_success`
--
ALTER TABLE `cp_success`
    MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 5;

--
-- AUTO_INCREMENT pour la table `cp_suggestion`
--
ALTER TABLE `cp_suggestion`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 5;

--
-- AUTO_INCREMENT pour la table `cp_tag`
--
ALTER TABLE `cp_tag`
    MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 5;

--
-- AUTO_INCREMENT pour la table `cp_user`
--
ALTER TABLE `cp_user`
    MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `cp_achieve`
--
ALTER TABLE `cp_achieve`
    ADD CONSTRAINT `fk_achieve_player_uuid` FOREIGN KEY (`player_uuid`) REFERENCES `cp_player` (`uuid`),
    ADD CONSTRAINT `fk_achieve_success_id` FOREIGN KEY (`success_id`) REFERENCES `cp_success` (`id`);

--
-- Contraintes pour la table `cp_game_record`
--
ALTER TABLE `cp_game_record`
    ADD CONSTRAINT `fk_game_record_game_id` FOREIGN KEY (`game_id`) REFERENCES `cp_game` (`id`),
    ADD CONSTRAINT `fk_game_record_hosted_uuid` FOREIGN KEY (`hosted_by`) REFERENCES `cp_player` (`uuid`);

--
-- Contraintes pour la table `cp_invoice`
--
ALTER TABLE `cp_invoice`
    ADD CONSTRAINT `fk_invoice_player_uuid` FOREIGN KEY (`player_uuid`) REFERENCES `cp_player` (`uuid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `cp_invoice_row`
--
ALTER TABLE `cp_invoice_row`
    ADD CONSTRAINT `fk_invoice_row_article_id` FOREIGN KEY (`article_id`) REFERENCES `cp_article` (`id`),
    ADD CONSTRAINT `fk_invoice_row_invoice_id` FOREIGN KEY (`invoice_id`) REFERENCES `cp_invoice` (`id`);

--
-- Contraintes pour la table `cp_moderator`
--
ALTER TABLE `cp_moderator`
    ADD CONSTRAINT `fk_moderator_user_id` FOREIGN KEY (`user_id`) REFERENCES `cp_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `cp_penalty`
--
ALTER TABLE `cp_penalty`
    ADD CONSTRAINT `fk_penalty_moderator_cancelled_uuid` FOREIGN KEY (`cancelled_by`) REFERENCES `cp_moderator` (`uuid`),
    ADD CONSTRAINT `fk_penalty_moderator_creation_uuid` FOREIGN KEY (`created_by`) REFERENCES `cp_moderator` (`uuid`),
    ADD CONSTRAINT `fk_penalty_player_uuid` FOREIGN KEY (`penalized_uuid`) REFERENCES `cp_player` (`uuid`);

--
-- Contraintes pour la table `cp_played`
--
ALTER TABLE `cp_played`
    ADD CONSTRAINT `fk_played_game_uuid` FOREIGN KEY (`game_uuid`) REFERENCES `cp_game_record` (`uuid`),
    ADD CONSTRAINT `fk_played_player_uuid` FOREIGN KEY (`player_uuid`) REFERENCES `cp_player` (`uuid`);

--
-- Contraintes pour la table `cp_player`
--
ALTER TABLE `cp_player`
    ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `cp_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `cp_pswd_reset_token`
--
ALTER TABLE `cp_pswd_reset_token`
    ADD CONSTRAINT `fk_pswd_reset_user_id` FOREIGN KEY (`user_id`) REFERENCES `cp_user` (`id`);

--
-- Contraintes pour la table `cp_report`
--
ALTER TABLE `cp_report`
    ADD CONSTRAINT `fk_report_moderator_uuid` FOREIGN KEY (`treated_by`) REFERENCES `cp_moderator` (`uuid`),
    ADD CONSTRAINT `fk_report_player_reported` FOREIGN KEY (`reported_uuid`) REFERENCES `cp_player` (`uuid`),
    ADD CONSTRAINT `fk_report_player_sender` FOREIGN KEY (`sender_uuid`) REFERENCES `cp_player` (`uuid`);

--
-- Contraintes pour la table `cp_success`
--
ALTER TABLE `cp_success`
    ADD CONSTRAINT `fk_success_success_dependent` FOREIGN KEY (`depend_on`) REFERENCES `cp_success` (`id`);

--
-- Contraintes pour la table `cp_suggestion`
--
ALTER TABLE `cp_suggestion`
    ADD CONSTRAINT `fk_author_uuid` FOREIGN KEY (`author_uuid`) REFERENCES `cp_player` (`uuid`),
    ADD CONSTRAINT `fk_treated_by` FOREIGN KEY (`treated_by`) REFERENCES `cp_moderator` (`uuid`);

--
-- Contraintes pour la table `cp_tagged`
--
ALTER TABLE `cp_tagged`
    ADD CONSTRAINT `fk_tagged_game_id` FOREIGN KEY (`game_id`) REFERENCES `cp_game` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_tagged_tag_id` FOREIGN KEY (`tag_id`) REFERENCES `cp_tag` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `cp_won`
--
ALTER TABLE `cp_won`
    ADD CONSTRAINT `fk_winned_game_uuid` FOREIGN KEY (`game_uuid`) REFERENCES `cp_game_record` (`uuid`),
    ADD CONSTRAINT `fk_winned_player_uuid` FOREIGN KEY (`player_uuid`) REFERENCES `cp_player` (`uuid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
