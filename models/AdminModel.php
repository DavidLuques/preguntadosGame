<?php

class AdminModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getStatistics()
    {
        $stats = [];

        // Total games played
        $sqlGames = "SELECT SUM(games_played) as total_games FROM user WHERE rol = 'JUGADOR'";
        $resultGames = $this->database->query($sqlGames);
        $stats['total_games'] = $resultGames[0]['total_games'] ?? 0;

        // Total score
        $sqlScore = "SELECT SUM(total_score) as total_score FROM user WHERE rol = 'JUGADOR'";
        $resultScore = $this->database->query($sqlScore);
        $stats['total_score'] = $resultScore[0]['total_score'] ?? 0;

        // Users by difficulty
        $sqlDifficulty = "SELECT difficulty_level, COUNT(*) as count 
                          FROM user 
                          WHERE rol = 'JUGADOR' 
                          GROUP BY difficulty_level";
        $resultDifficulty = $this->database->query($sqlDifficulty);

        $stats['difficulty_levels'] = [];
        $stats['difficulty_levels']['Principiante'] = 0;
        $stats['difficulty_levels']['Medio'] = 0;
        $stats['difficulty_levels']['Avanzado'] = 0;
        $stats['difficulty_levels']['Sin Clasificar'] = 0;

        foreach ($resultDifficulty as $row) {
            $level = $row['difficulty_level'];
            if ($level === 'Principiante' || $level === 'Medio' || $level === 'Avanzado') {
                $stats['difficulty_levels'][$level] = $row['count'];
            } else {
                $stats['difficulty_levels']['Sin Clasificar'] += $row['count'];
            }
        }

        return $stats;
    }
}
