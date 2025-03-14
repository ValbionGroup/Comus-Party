<?php
/**
 * @file    EloCalculator.php
 * @brief   Fichier de déclaration et définition de la classe EloCalculator.
 * @date    12/11/2024
 * @version 0.1
 */

namespace ComusParty\App;

/**
 * @brief Classe EloCalculator
 */
class EloCalculator
{
    /**
     * @brief Facteur K
     * @var int
     */
    const int K_FACTOR = 16;

    /**
     * @brief Calcule le nouvel Elo du joueur en fonction de son Elo actuel, de l'Elo moyen des joueurs de la partie et du résultat de la partie
     * @param $eloPlayer int Elo du joueur
     * @param $averageElo float Elo moyen des joueurs de la partie
     * @param $result float Résultat de la partie (1 pour une victoire, 0.5 pour un match nul, 0 pour une défaite)
     * @return float Nouvel Elo du joueur
     */
    public static function calculateNewElo(int $eloPlayer, float $averageElo, float $result): float
    {
        return $eloPlayer + self::K_FACTOR * ($result - self::getExpectedScore($eloPlayer, $averageElo));
    }

    /**
     * @brief Calcule la probabilité de victoire en fonction de l'Elo passé en paramètre
     * @param $eloPlayer int Elo du joueur
     * @param $averageElo float Elo moyen des joueurs de la partie
     * @return float Probabilité de victoire
     */
    protected static function getExpectedScore(int $eloPlayer, float $averageElo): float
    {
        return 1 / (1 + pow(10, ($averageElo - $eloPlayer) / 400));
    }
}