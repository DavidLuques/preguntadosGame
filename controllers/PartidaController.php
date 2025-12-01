<?php
include_once(__DIR__ . "/../models/PartidaModel.php");

class PartidaController
{
    private $model;
    private $renderer;

    public function __construct($partidaModel, $renderer)
    {
        $this->model = $partidaModel;
        $this->renderer = $renderer;
    }

    public function base()
    {
        if (isset($_SESSION['rol'])) {
            if ($_SESSION['rol'] === 'editor') {
                header("Location: /editor/dashboard");
                exit();
            }
            if ($_SESSION['rol'] === 'ADMIN') {
                header("Location: /admin/dashboard");
                exit();
            }
        }
        $this->partidaComienzo();
    }

    public function partidaComienzo()
    {
        $this->renderer->render("inicioPartida");
    }

    public function iniciarPartida()
    {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: /login/login");
            exit();
        }

        $_SESSION['partida_activa'] = true;
        $_SESSION['partida_preguntas_jugadas'] = 0; 
        $_SESSION['partida_puntos'] = 0;
        $_SESSION['partida_respuestas'] = [];
        unset($_SESSION['current_question']); 
        unset($_SESSION['question_start_time']); 

        $this->model->incrementarPartidasJugadas($_SESSION['usuario_id']);

        header("Location: /partida/ruleta");
        exit();
    }

    public function ruleta()
    {
        if (!isset($_SESSION['partida_activa']) || !$_SESSION['partida_activa']) {
            header("Location: /partida/inicioPartida");
            exit();
        }

        if ($_SESSION['partida_preguntas_jugadas'] >= 10) {
            header("Location: /partida/resultados");
            exit();
        }

        if (isset($_SESSION['current_question'])) {
            header("Location: /partida/jugarPregunta");
            exit();
        }

        $progreso = [];
        $respuestas = isset($_SESSION['partida_respuestas']) ? $_SESSION['partida_respuestas'] : [];
        
        for ($i = 0; $i < 10; $i++) {
            if (isset($respuestas[$i])) {
                $esCorrecto = $respuestas[$i]['correcto'];
                $progreso[] = [
                    'numero' => $i + 1,
                    'estado' => $esCorrecto ? 'correct' : 'incorrect',
                    'clase' => $esCorrecto ? 'bg-success' : 'bg-danger',
                    'icono' => $esCorrecto ? 'bi-check-lg' : 'bi-x-lg'
                ];
            } else {
                $progreso[] = [
                    'numero' => $i + 1,
                    'estado' => 'pending',
                    'clase' => 'bg-secondary',
                    'icono' => 'bi-circle'
                ];
            }
        }

        $this->renderer->render("partida", ['progreso' => $progreso]); 
    }

    public function girarRuleta()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['partida_activa']) || !$_SESSION['partida_activa']) {
            echo json_encode(['error' => 'No hay partida activa']);
            exit();
        }

        if (isset($_SESSION['current_question'])) {
             echo json_encode(['error' => 'Ya tienes una pregunta asignada', 'redirect' => '/partida/jugarPregunta']);
             exit();
        }

        $userId = intval($_SESSION['usuario_id']);
        $difficultyLevel = $this->model->getDifficultyLevelByUserId($userId);

        $categories = [
            ['id' => 1, 'name' => 'Historia'],
            ['id' => 2, 'name' => 'Ciencia'],
            ['id' => 3, 'name' => 'Deportes'],
            ['id' => 4, 'name' => 'Geografía'],
            ['id' => 5, 'name' => 'Entretenimiento']
        ];
        
        $selectedCategory = $categories[array_rand($categories)];
        
        $playedQuestions = [];
        if (isset($_SESSION['partida_respuestas']) && is_array($_SESSION['partida_respuestas'])) {
            foreach ($_SESSION['partida_respuestas'] as $respuesta) {
                if (isset($respuesta['question_id'])) {
                    $playedQuestions[] = $respuesta['question_id'];
                }
            }
        }

        $pregunta = $this->model->getQuestionByCategoryAndDifficulty($selectedCategory['id'], $difficultyLevel, $playedQuestions);

        if (!$pregunta) {
             echo json_encode(['error' => 'No hay preguntas disponibles para esta categoría']);
             exit();
        }

        $_SESSION['current_question'] = $pregunta;
        unset($_SESSION['question_start_time']);

        echo json_encode([
            'success' => true,
            'category_id' => $selectedCategory['id'],
            'category_name' => $selectedCategory['name']
        ]);
        exit();
    }

    public function jugarPregunta()
    {
        if (!isset($_SESSION['partida_activa']) || !$_SESSION['partida_activa']) {
            header("Location: /partida/inicioPartida");
            exit();
        }

        if (!isset($_SESSION['current_question'])) {
            header("Location: /partida/ruleta");
            exit();
        }

        $pregunta = $_SESSION['current_question'];
        $questionId = intval($pregunta['question_id']);

        if (!isset($_SESSION['question_viewed_' . $questionId])) {
            $this->model->incrementarViewCount($questionId);
            $_SESSION['question_viewed_' . $questionId] = true;
        }

        if (!isset($_SESSION['question_start_time'])) {
            $_SESSION['question_start_time'] = microtime(true);
            $remainingTime = 10;
        } else {
            $elapsed = microtime(true) - $_SESSION['question_start_time'];
            $remainingTime = 10 - $elapsed;
            if ($remainingTime < 0) $remainingTime = 0;
        }

        $respuestas = $this->model->getAnswersByQuestionId($questionId);

        $this->renderer->render("jugarPregunta", [
            'pregunta' => $pregunta,
            'respuestas' => $respuestas,
            'pregunta_numero' => $_SESSION['partida_preguntas_jugadas'] + 1,
            'total_preguntas' => 10,
            'remaining_time' => number_format($remainingTime, 2, '.', ''),
            'remaining_time_int' => ceil($remainingTime), 
            'category_color' => $this->getCategoryColor($pregunta['category_id']),
            'reportSuccess' => isset($_GET['report']) && $_GET['report'] === 'success',
            'reportError' => isset($_GET['reportError']) ? urldecode($_GET['reportError']) : null
        ]);
    }

    private function getCategoryColor($categoryId)
    {
        switch ($categoryId) {
            case 1: return '#E91E63'; 
            case 2: return '#a67df3ff'; 
            case 3: return '#055685ff'; 
            case 4: return '#0dcaf0'; 
            case 5: return '#19c072ff'; 
            default: return '#6C757D'; 
        }
    }

    public function procesarRespuesta()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /partida/inicioPartida");
            exit();
        }

        if (!isset($_SESSION['partida_activa']) || !$_SESSION['partida_activa']) {
            header("Location: /partida/inicioPartida");
            exit();
        }

        $questionId = isset($_POST['question_id']) ? intval($_POST['question_id']) : 0;
        $answerId = isset($_POST['answer_id']) ? intval($_POST['answer_id']) : 0;

        if (!isset($_SESSION['current_question']) || $_SESSION['current_question']['question_id'] != $questionId) {
            header("Location: /partida/jugarPregunta");
            exit();
        }

        $tiempoAgotado = false;
        if (isset($_SESSION['question_start_time'])) {
            $tiempoTranscurrido = microtime(true) - $_SESSION['question_start_time'];
            if ($tiempoTranscurrido > 12) {
                $tiempoAgotado = true;
            }
        }

        $pregunta = $_SESSION['current_question'];
        $correcto = false;

        if (!$tiempoAgotado && intval($pregunta['correct_answer_id']) === $answerId) {
            $correcto = true;
            $_SESSION['partida_puntos'] = intval($_SESSION['partida_puntos']) + 1;
            $this->model->incrementarCorrectAnswerCount($questionId);
        }

        $_SESSION['partida_respuestas'][] = [
            'question_id' => $questionId,
            'answer_id' => $answerId,
            'correcto' => $correcto
        ];

        $_SESSION['partida_preguntas_jugadas']++;
        
        $respuestaCorrectaTexto = "Desconocida";
        $respuestas = $this->model->getAnswersByQuestionId($questionId);
        foreach ($respuestas as $resp) {
            if ($resp['answer_id'] == $pregunta['correct_answer_id']) {
                $respuestaCorrectaTexto = $resp['answer_text'];
                break;
            }
        }

        $_SESSION['last_result'] = [
            'esCorrecto' => $correcto,
            'tiempoAgotado' => $tiempoAgotado,
            'respuestaCorrecta' => $respuestaCorrectaTexto,
            'question_text' => $pregunta['question_text'],
            'question_id' => $questionId,
            'siguienteUrl' => ($_SESSION['partida_preguntas_jugadas'] >= 10) ? '/partida/resultados' : '/partida/ruleta'
        ];
        
        unset($_SESSION['current_question']);
        unset($_SESSION['question_start_time']);

        header("Location: /partida/respuesta");
        exit();
    }

    public function respuesta()
    {
        if (!isset($_SESSION['partida_activa']) || !$_SESSION['partida_activa']) {
            header("Location: /partida/inicioPartida");
            exit();
        }

        if (!isset($_SESSION['last_result'])) {
            if (isset($_SESSION['current_question'])) {
                header("Location: /partida/jugarPregunta");
            } else {
                header("Location: /partida/ruleta");
            }
            exit();
        }

        $data = $_SESSION['last_result'];
        
        if (isset($_GET['report']) && $_GET['report'] === 'success') {
            $data['reportSuccess'] = true;
        }
        if (isset($_GET['reportError'])) {
            $data['reportError'] = urldecode($_GET['reportError']);
        }
        
        if (isset($_SESSION['usuario'])) {
            $data['usuario'] = $_SESSION['usuario'];
        }

        $this->renderer->render("respuesta", $data);
    }

    public function resultados()
    {
        if (!isset($_SESSION['partida_activa']) || !$_SESSION['partida_activa']) {
            header("Location: /partida/inicioPartida");
            exit();
        }

        $puntos = intval($_SESSION['partida_puntos']);
        $totalPreguntas = 10; 

        if (isset($_SESSION['usuario_id'])) {
            $userId = intval($_SESSION['usuario_id']);
            $estadisticas = $this->model->actualizarEstadisticasUsuario($userId, $puntos);
        }

        unset($_SESSION['partida_activa']);
        unset($_SESSION['partida_preguntas_jugadas']);
        unset($_SESSION['partida_puntos']);
        unset($_SESSION['partida_respuestas']);
        unset($_SESSION['current_question']);
        unset($_SESSION['last_result']); 

        $this->renderer->render("resultadosPartida", [
            'puntos' => $puntos,
            'total_preguntas' => $totalPreguntas,
            'estadisticas' => $estadisticas ?? null
        ]);
    }

    public function reportarPregunta()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /partida');
            exit();
        }

        $questionId = isset($_POST['question_id']) ? intval($_POST['question_id']) : null;
        $reason = isset($_POST['reason']) ? trim($_POST['reason']) : '';
        $fromFeedback = isset($_POST['from_feedback']) && $_POST['from_feedback'] === 'true';

        if (!$questionId) {
            header('Location: /partida');
            exit();
        }

        if ($reason === '') {
            $error = urlencode('Debés indicar un motivo para reportar la pregunta.');
            if ($fromFeedback) {
                header("Location: /partida/respuesta?reportError=$error");
            } elseif (isset($_SESSION['partida_activa']) && $_SESSION['partida_activa']) {
                header("Location: /partida/jugarPregunta?reportError=$error");
            } else {
                header("Location: /partida/inicioPartida?reportError=$error");
            }
            exit();
        }

        if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario_id'])) {
            header("Location: /login/login");
            exit();
        }

        $userId = intval($_SESSION['usuario_id']);
        $resultado = $this->model->guardarReportePregunta($questionId, $userId, $reason);

        if (isset($resultado['success']) && $resultado['success'] === true) {
            if ($fromFeedback) {
                header("Location: /partida/respuesta?report=success");
            } elseif (isset($_SESSION['partida_activa']) && $_SESSION['partida_activa']) {
                header("Location: /partida/jugarPregunta?report=success");
            } else {
                header("Location: /partida/inicioPartida?report=success");
            }
        } else {
            $mensajeError = isset($resultado['error'])
                ? $resultado['error']
                : 'No pudimos guardar tu reporte. Intentá nuevamente.';
            $error = urlencode($mensajeError);
            if ($fromFeedback) {
                header("Location: /partida/respuesta?reportError=$error");
            } elseif (isset($_SESSION['partida_activa']) && $_SESSION['partida_activa']) {
                header("Location: /partida/jugarPregunta?reportError=$error");
            } else {
                header("Location: /partida/inicioPartida?reportError=$error");
            }
        }

        exit();
    }
}
