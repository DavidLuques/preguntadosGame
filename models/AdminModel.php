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

        $sqlGames = "SELECT SUM(games_played) as total_games FROM user WHERE rol = 'JUGADOR'";
        $resultGames = $this->database->query($sqlGames);
        $stats['total_games'] = $resultGames[0]['total_games'] ?? 0;

        $sqlScore = "SELECT SUM(total_score) as total_score FROM user WHERE rol = 'JUGADOR'";
        $resultScore = $this->database->query($sqlScore);
        $stats['total_score'] = $resultScore[0]['total_score'] ?? 0;

        $sqlPlayers = "SELECT COUNT(*) as total_players FROM user WHERE rol = 'JUGADOR'";
        $resultPlayers = $this->database->query($sqlPlayers);
        $stats['total_players'] = $resultPlayers[0]['total_players'] ?? 0;

        $sqlQuestions = "SELECT COUNT(*) as total_questions FROM question";
        $resultQuestions = $this->database->query($sqlQuestions);
        $stats['total_questions'] = $resultQuestions[0]['total_questions'] ?? 0;

        $sqlStatus = "SELECT status, COUNT(*) as count FROM question GROUP BY status";
        $resultStatus = $this->database->query($sqlStatus);
        
        $stats['active_questions'] = 0;
        $stats['suggested_questions'] = 0;
        $stats['rejected_questions'] = 0;

        foreach ($resultStatus as $row) {
            $status = strtolower($row['status']);
            if ($status === 'activa') {
                $stats['active_questions'] += $row['count'];
            } elseif ($status === 'sugerida') {
                $stats['suggested_questions'] += $row['count'];
            } elseif ($status === 'rechazada') {
                $stats['rejected_questions'] += $row['count'];
            }
        }

        $sqlDifficulty = "SELECT difficulty_level, COUNT(*) as count 
                          FROM user 
                          WHERE rol = 'JUGADOR' 
                          GROUP BY difficulty_level";
        $resultDifficulty = $this->database->query($sqlDifficulty);

        $stats['difficulty_levels'] = [];
        $stats['difficulty_levels']['Principiante'] = 0;
        $stats['difficulty_levels']['Medio'] = 0;
        $stats['difficulty_levels']['Avanzado'] = 0;

        foreach ($resultDifficulty as $row) {
            $level = $row['difficulty_level'];
            if ($level === 'Principiante' || $level === 'Medio' || $level === 'Avanzado') {
                $stats['difficulty_levels'][$level] = $row['count'];
            }
        }

        $sqlCategory = "SELECT c.category_name, COUNT(q.question_id) as count 
                        FROM question q 
                        JOIN category c ON q.category_id = c.category_id 
                        GROUP BY c.category_name";
        $resultCategory = $this->database->query($sqlCategory);
        
        $stats['questions_by_category'] = [];
        if ($resultCategory) {
            foreach ($resultCategory as $row) {
                $stats['questions_by_category'][$row['category_name']] = $row['count'];
            }
        }

        return $stats;
    }

    public function getAllUsers()
    {
        $sql = "SELECT id, username, email, rol, created_at FROM user ORDER BY created_at DESC";
        return $this->database->query($sql);
    }

    public function updateUserRole($userId, $newRole)
    {
        $userId = intval($userId);
        $newRole = $this->database->getConnection()->real_escape_string($newRole);
        
        $sql = "UPDATE user SET rol = '$newRole' WHERE id = $userId";
        return $this->database->query($sql);
    }
}
