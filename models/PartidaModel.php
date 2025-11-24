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
}
