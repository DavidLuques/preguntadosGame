<?php

class PartidaModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function getRandomQuestionByCategory($categoryId)
    {
        $categoryId = intval($categoryId);
        $sql = "SELECT * FROM question WHERE category_id = $categoryId ORDER BY RAND() LIMIT 1";
        return $this->conexion->query($sql);
    }

    public function getQuestionById($questionId)
    {
        $questionId = intval($questionId);
        $sql = "SELECT * FROM question WHERE question_id = $questionId LIMIT 1";
        $resultado = $this->conexion->query($sql);

        if ($resultado && !empty($resultado)) {
            return $resultado[0];
        }

        return null;
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

        // Agregar claves foráneas solo si la tabla se acaba de crear
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

    public function getQuestionByDifficulty($difficultyLevel)
    {
        $conn = $this->conexion->getConnection();
        
        // Mapeo de dificultad del usuario a nivel de pregunta:
        // Principiante/Fácil -> 'easy'
        // Medio -> 'medium'
        // Avanzado/Difícil -> 'hard'
        $questionDifficulty = 'easy'; // Por defecto fácil
        if ($difficultyLevel === 'Medio') {
            $questionDifficulty = 'medium';
        } elseif ($difficultyLevel === 'Dificil' || $difficultyLevel === 'Avanzado') {
            $questionDifficulty = 'hard';
        }
        
        $questionDifficultyEscaped = $conn->real_escape_string($questionDifficulty);
        
        // Buscar preguntas:
        // 1. Preguntas con difficulty_level NULL (se muestran a todos los niveles)
        // 2. Preguntas con difficulty_level que coincida con el nivel del usuario
        // Acepta tanto 'activa' como 'active' como estados válidos
        $sql = "SELECT * FROM question 
                WHERE (status = 'activa' OR status = 'active')
                AND (difficulty_level IS NULL OR difficulty_level = '$questionDifficultyEscaped')
                ORDER BY RAND() LIMIT 1";
        $result = $this->conexion->query($sql);
        
        if ($result && !empty($result)) {
            return $result[0];
        }
        
        // Si no hay preguntas, buscar cualquier pregunta activa
        $sql = "SELECT * FROM question WHERE (status = 'activa' OR status = 'active') ORDER BY RAND() LIMIT 1";
        $result = $this->conexion->query($sql);
        
        return ($result && !empty($result)) ? $result[0] : null;
    }

    public function incrementarViewCount($questionId)
    {
        $questionId = intval($questionId);
        $conn = $this->conexion->getConnection();
        
        // Asegurar que view_count y correct_answer_count existen, inicializarlos si no existen
        $conn->query("UPDATE question SET view_count = COALESCE(view_count, 0) WHERE question_id = $questionId");
        $conn->query("UPDATE question SET correct_answer_count = COALESCE(correct_answer_count, 0) WHERE question_id = $questionId");
        
        // Incrementar view_count
        $sql = "UPDATE question SET view_count = view_count + 1 WHERE question_id = $questionId";
        $conn->query($sql);
        
        // NO establecer difficulty_id automáticamente - se mantiene NULL para que se muestre a todos
    }

    public function incrementarCorrectAnswerCount($questionId)
    {
        $questionId = intval($questionId);
        $conn = $this->conexion->getConnection();
        
        // Asegurar que correct_answer_count existe y actualizarlo
        $sql = "UPDATE question SET correct_answer_count = COALESCE(correct_answer_count, 0) + 1 WHERE question_id = $questionId";
        $conn->query($sql);
        
        // Actualizar difficulty_id basado en el ratio
        $this->actualizarDificultadPregunta($questionId);
    }

    private function actualizarDificultadPregunta($questionId)
    {
        $questionId = intval($questionId);
        $conn = $this->conexion->getConnection();
        
        // Obtener view_count y correct_answer_count
        $sql = "SELECT view_count, correct_answer_count FROM question WHERE question_id = $questionId";
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $viewCount = intval($row['view_count'] ?? 0);
            $correctCount = intval($row['correct_answer_count'] ?? 0);
            
            // Solo actualizar dificultad cuando view_count >= 10
            if ($viewCount >= 10 && $viewCount > 0) {
                $ratio = ($correctCount / $viewCount) * 100;
                
                // Calcular nueva dificultad según los criterios:
                // Hard: < 40% de aciertos
                // Medium: >= 40% y < 70% de aciertos
                // Easy: >= 70% de aciertos
                $newDifficultyLevel = 'easy'; // Easy por defecto (>= 70%)
                
                if ($ratio < 40) {
                    $newDifficultyLevel = 'hard'; // Hard (< 40%)
                } elseif ($ratio >= 40 && $ratio < 70) {
                    $newDifficultyLevel = 'medium'; // Medium (>= 40% y < 70%)
                } else {
                    $newDifficultyLevel = 'easy'; // Easy (>= 70%)
                }
                
                // Actualizar difficulty_level solo si tiene 10 o más vistas
                $newDifficultyLevelEscaped = $conn->real_escape_string($newDifficultyLevel);
                $updateSql = "UPDATE question SET difficulty_level = '$newDifficultyLevelEscaped' WHERE question_id = $questionId";
                $conn->query($updateSql);
            }
        }
    }

    public function actualizarEstadisticasUsuario($userId, $puntosObtenidos)
    {
        $userId = intval($userId);
        $puntosObtenidos = intval($puntosObtenidos);
        $conn = $this->conexion->getConnection();
        
        // Obtener estadísticas actuales
        $sql = "SELECT total_score, games_played FROM user WHERE id = $userId";
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $totalScore = intval($row['total_score'] ?? 0);
            $gamesPlayed = intval($row['games_played'] ?? 0);
            
            // Actualizar total_score y games_played
            $newTotalScore = $totalScore + $puntosObtenidos;
            $newGamesPlayed = $gamesPlayed + 1;
            
            // Calcular nuevo nivel de dificultad
            $ratio = ($newGamesPlayed > 0) ? ($newTotalScore / ($newGamesPlayed * 10)) * 100 : 0;
            
            $newDifficultyLevel = 'Principiante';
            if ($ratio >= 70) {
                $newDifficultyLevel = 'Avanzado';
            } elseif ($ratio >= 40) {
                $newDifficultyLevel = 'Medio';
            }
            
            // Actualizar en base de datos
            $updateSql = "UPDATE user SET total_score = $newTotalScore, games_played = $newGamesPlayed, difficulty_level = '$newDifficultyLevel' WHERE id = $userId";
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
