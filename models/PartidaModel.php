<?php

class PartidaModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function guardarReportePregunta($questionId, $userId, $reason)
    {
        $questionId = intval($questionId);
        $userId = intval($userId);
        $conn = $this->conexion->getConnection();

        if ($questionId <= 0 || $userId <= 0) {
            return ['success' => false, 'error' => 'No pudimos identificar al usuario o la pregunta. Reintentá iniciando sesión nuevamente.'];
        }

        if (!$this->asegurarTablaReport($conn)) {
            return ['success' => false, 'error' => 'No se pudo preparar la tabla de reportes.'];
        }

        $reason = trim($reason);
        if ($reason === '') {
            return ['success' => false, 'error' => 'El motivo del reporte no puede estar vacío.'];
        }

        $reason = mb_substr($reason, 0, 255);
        $reasonEscaped = $conn->real_escape_string($reason);
        $reportDate = date('Y-m-d');

        $sql = "INSERT INTO report (question_id, report_date, user_id, reason) 
                VALUES ($questionId, '$reportDate', $userId, '$reasonEscaped')";

        $result = $conn->query($sql);

        if (!$result) {
            $errorMessage = "Error guardando reporte: " . $conn->error;
            error_log($errorMessage);
            return ['success' => false, 'error' => 'Error en la base de datos: ' . $conn->error];
        }

        return ['success' => true];
    }

    private function asegurarTablaReport($conn)
    {
        $result = $conn->query("SHOW TABLES LIKE 'report'");
        if ($result && $result->num_rows > 0) {
            $result->free();
            return true;
        }

        $sql = "CREATE TABLE IF NOT EXISTS `report` (
            `report_id` int(11) NOT NULL AUTO_INCREMENT,
            `question_id` int(11) DEFAULT NULL,
            `invalid_question` char(1) DEFAULT NULL,
            `report_date` date DEFAULT NULL,
            `user_id` int(11) DEFAULT NULL,
            `reason` varchar(255) DEFAULT NULL,
            PRIMARY KEY (`report_id`),
            KEY `fk_report_question` (`question_id`),
            KEY `fk_report_user` (`user_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

        $crearTabla = $conn->query($sql);
        if (!$crearTabla) {
            error_log("No se pudo crear la tabla report: " . $conn->error);
            return false;
        }

        $conn->query("ALTER TABLE `report`
            ADD CONSTRAINT `fk_report_question` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`) ON DELETE SET NULL,
            ADD CONSTRAINT `fk_report_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE");

        return true;
    }

    public function getAnswersByQuestionId($questionId)
    {
        $questionId = intval($questionId);
        $sql = "SELECT * FROM answer WHERE question_id = $questionId ORDER BY RAND()";
        return $this->conexion->query($sql);
    }

    public function getQuestionByCategoryAndDifficulty($categoryId, $difficultyLevel, $playedQuestions = [])
    {
        $conn = $this->conexion->getConnection();
        $categoryId = intval($categoryId);
        
        if (empty($difficultyLevel)) {
            $difficultyLevel = 'Principiante';
        }
        
        $notInClause = "";
        if (!empty($playedQuestions)) {
            $ids = implode(',', array_map('intval', $playedQuestions));
            $notInClause = "AND question_id NOT IN ($ids)";
        }
        
        $buscarPregunta = function($nivel) use ($conn, $categoryId, $notInClause) {
            $nivelEscaped = $conn->real_escape_string($nivel);
            $sql = "SELECT * FROM question 
                    WHERE category_id = $categoryId
                    AND status = 'activa'
                    AND difficulty_level = '$nivelEscaped'
                    $notInClause
                    ORDER BY RAND() LIMIT 1";
            $result = $conn->query($sql);
            return ($result && $result->num_rows > 0) ? $result->fetch_assoc() : null;
        };

        $pregunta = null;

        if ($difficultyLevel === 'Avanzado') {
            $pregunta = $buscarPregunta('Avanzado');
            if (!$pregunta) {
                $pregunta = $buscarPregunta('Medio');
            }
            if (!$pregunta) {
                $pregunta = $buscarPregunta('Principiante');
            }
        } elseif ($difficultyLevel === 'Medio') {
            $pregunta = $buscarPregunta('Medio');
            if (!$pregunta) {
                $pregunta = $buscarPregunta('Principiante');
            }
        } else {
            $pregunta = $buscarPregunta('Principiante');
        }
        
        if (!$pregunta) {
             $sql = "SELECT * FROM question 
                    WHERE category_id = $categoryId 
                    AND status = 'activa' 
                    $notInClause
                    ORDER BY RAND() LIMIT 1";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                $pregunta = $result->fetch_assoc();
            }
        }

        if (!$pregunta) {
             $sql = "SELECT * FROM question 
                    WHERE category_id = $categoryId 
                    AND status = 'activa' 
                    ORDER BY RAND() LIMIT 1";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                $pregunta = $result->fetch_assoc();
            }
        }

        return $pregunta;
    }

    public function incrementarViewCount($questionId)
    {
        $questionId = intval($questionId);
        $conn = $this->conexion->getConnection();
        
        $conn->query("UPDATE question SET view_count = COALESCE(view_count, 0) WHERE question_id = $questionId");
        $conn->query("UPDATE question SET correct_answer_count = COALESCE(correct_answer_count, 0) WHERE question_id = $questionId");
        
        $sql = "UPDATE question SET view_count = view_count + 1 WHERE question_id = $questionId";
        $conn->query($sql);
    }

    public function incrementarCorrectAnswerCount($questionId)
    {
        $questionId = intval($questionId);
        $conn = $this->conexion->getConnection();
        
        $sql = "UPDATE question SET correct_answer_count = COALESCE(correct_answer_count, 0) + 1 WHERE question_id = $questionId";
        $conn->query($sql);
        
        $this->actualizarDificultadPregunta($questionId);
    }

    private function actualizarDificultadPregunta($questionId)
    {
        $questionId = intval($questionId);
        $conn = $this->conexion->getConnection();
        
        $sql = "SELECT view_count, correct_answer_count FROM question WHERE question_id = $questionId";
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $viewCount = intval($row['view_count'] ?? 0);
            $correctCount = intval($row['correct_answer_count'] ?? 0);
            
            if ($viewCount >= 10 && $viewCount > 0) {
                $ratio = ($correctCount / $viewCount) * 100;
                
                $newDifficultyLevel = 'Principiante'; 
                
                if ($ratio < 30) {
                    $newDifficultyLevel = 'Avanzado';
                } elseif ($ratio >= 30 && $ratio < 70) {
                    $newDifficultyLevel = 'Medio';
                } else {
                    $newDifficultyLevel = 'Principiante';
                }
                
                $newDifficultyLevelEscaped = $conn->real_escape_string($newDifficultyLevel);
                $updateSql = "UPDATE question SET difficulty_level = '$newDifficultyLevelEscaped' WHERE question_id = $questionId";
                $conn->query($updateSql);
            }
        }
    }

    public function incrementarPartidasJugadas($userId)
    {
        $userId = intval($userId);
        $conn = $this->conexion->getConnection();
        $sql = "UPDATE user SET games_played = COALESCE(games_played, 0) + 1 WHERE id = $userId";
        $conn->query($sql);
    }

    public function actualizarEstadisticasUsuario($userId, $puntosObtenidos)
    {
        $userId = intval($userId);
        $puntosObtenidos = intval($puntosObtenidos);
        $conn = $this->conexion->getConnection();
        
        $sql = "SELECT total_score, games_played FROM user WHERE id = $userId";
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $totalScore = intval($row['total_score'] ?? 0);
            $gamesPlayed = intval($row['games_played'] ?? 0);
            
            $newTotalScore = $totalScore + $puntosObtenidos;
            $newGamesPlayed = $gamesPlayed; 
            
            $ratio = ($newGamesPlayed > 0) ? ($newTotalScore / ($newGamesPlayed * 10)) * 100 : 0;
            
            $newDifficultyLevel = 'Principiante';
            if ($ratio >= 70) {
                $newDifficultyLevel = 'Avanzado';
            } elseif ($ratio >= 40) {
                $newDifficultyLevel = 'Medio';
            }
            
            $updateSql = "UPDATE user SET total_score = $newTotalScore, difficulty_level = '$newDifficultyLevel' WHERE id = $userId";
            $conn->query($updateSql);
            
            return ['total_score' => $newTotalScore, 'games_played' => $newGamesPlayed, 'difficulty_level' => $newDifficultyLevel];
        }
        
        return null;
    }

    public function getDifficultyLevelByUserId($userId)
    {
        $userId = intval($userId);
        $conn = $this->conexion->getConnection();
        $sql = "SELECT difficulty_level FROM user WHERE id = $userId";
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['difficulty_level'] ?? 'Principiante';
        }
        
        return 'Principiante';
    }
}
