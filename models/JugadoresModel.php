<?php

class JugadoresModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function mostrarTabla()
    {
        $sql = "SELECT username as usuario, 
                location, 
                total_score as puntajeTotal, profile_picture as fotoPerfil,
                id as id
                FROM user
                WHERE rol = 'JUGADOR' AND total_score IS NOT NULL
                ORDER BY total_score DESC
                LIMIT 7";

        $jugadores = $this->conexion->query($sql);

        if ($jugadores === null) {
            return [];
        }

        if (is_array($jugadores) && !empty($jugadores)) {
            $posicion = 1;
            foreach ($jugadores as &$jugador) {
                $jugador['posicion'] = $posicion;
                $posicion++;
            }
        }

        return $jugadores ?? [];
    }

    public function crearPreguntaSugerida($text, $categoryId, $answers, $correctAnswerIndex)
    {
        $conn = $this->conexion->getConnection();

        // 1. Insertar pregunta (status='sugerida', difficulty='Principiante')
        $textEscaped = $conn->real_escape_string($text);
        $categoryId = intval($categoryId);
        $date = date('Y-m-d H:i:s');

        // Usamos 'question_date' ya que confirmamos que es la columna correcta
        $sqlQuestion = "INSERT INTO question (question_text, question_date, category_id, status, difficulty_level, view_count, correct_answer_count) 
                        VALUES ('$textEscaped', '$date', $categoryId, 'sugerida', 'Principiante', 0, 0)";

        if (!$conn->query($sqlQuestion)) {
            return false;
        }

        $questionId = $conn->insert_id;
        $correctAnswerId = null;

        // 2. Insertar respuestas
        foreach ($answers as $index => $ansText) {
            $ansTextEscaped = $conn->real_escape_string($ansText);
            $sqlAnswer = "INSERT INTO answer (answer_text, question_id) VALUES ('$ansTextEscaped', $questionId)";

            if ($conn->query($sqlAnswer)) {
                $answerId = $conn->insert_id;
                if (intval($index) === intval($correctAnswerIndex)) {
                    $correctAnswerId = $answerId;
                }
            }
        }

        // 3. Actualizar respuesta correcta en la pregunta
        if ($correctAnswerId) {
            $sqlUpdate = "UPDATE question SET correct_answer_id = $correctAnswerId WHERE question_id = $questionId";
            $conn->query($sqlUpdate);
        }

        return true;
    }
}
