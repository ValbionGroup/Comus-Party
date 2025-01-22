<?php

namespace ComusParty\App;

class EloCalculator
{
    const K_FACTOR = 16;

    /**
     * @brief Calcule le nouvel Elo du joueur en fonction de son Elo actuel, de l'Elo moyen des joueurs de la partie et du résultat de la partie
     * @param $eloPlayer int Elo du joueur
     * @param $averageElo int Elo moyen des joueurs de la partie
     * @param $result float Résultat de la partie (1 pour une victoire, 0.5 pour un match nul, 0 pour une défaite)
     * @return int Nouvel Elo du joueur
     */
    public static function calculateNewElo(int $eloPlayer, int $averageElo, float $result)
    {
        return $eloPlayer + self::K_FACTOR * ($result - self::getExpectedScore($eloPlayer, $averageElo));
    }

    /**
     * @brief Calcule la probabilité de victoire en fonction de l'Elo passé en paramètre
     * @param $eloPlayer int Elo du joueur
     * @param $averageElo int Elo moyen des joueurs de la partie
     * @return float|int
     */
    protected static function getExpectedScore(int $eloPlayer, int $averageElo)
    {
        return 1 / (1 + pow(10, ($averageElo - $eloPlayer) / 400));
    }
}