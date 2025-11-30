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

        // Inicializar partida en sesión
        $_SESSION['partida_activa'] = true;
        $_SESSION['partida_preguntas_jugadas'] = 0; // Contador de preguntas
        $_SESSION['partida_puntos'] = 0;
        $_SESSION['partida_respuestas'] = [];
        unset($_SESSION['current_question']); // Limpiar pregunta actual

        // Redirigir a la ruleta
        header("Location: /partida/ruleta");
        exit();
    }

    public function ruleta()
    {
        if (!isset($_SESSION['partida_activa']) || !$_SESSION['partida_activa']) {
            header("Location: /partida/inicioPartida");
            exit();
        }

        // Verificar si ya se jugaron 10 preguntas
        if ($_SESSION['partida_preguntas_jugadas'] >= 10) {
            header("Location: /partida/resultados");
            exit();
        }

        // Si ya hay una pregunta seleccionada (por ejemplo, si recarga), redirigir a jugar
        if (isset($_SESSION['current_question'])) {
            header("Location: /partida/jugarPregunta");
            exit();
        }

        // Preparar datos de progreso
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

        $this->renderer->render("partida", ['progreso' => $progreso]); // Vista de la ruleta
    }

    public function girarRuleta()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['partida_activa']) || !$_SESSION['partida_activa']) {
            echo json_encode(['error' => 'No hay partida activa']);
            exit();
        }

        if (isset($_SESSION['current_question'])) {
             // Si ya hay pregunta, devolver la categoría de esa pregunta para que la ruleta gire a ella
             // O simplemente redirigir (el frontend manejará esto)
             // Para simplificar, asumimos que si llama a girar es porque no tiene pregunta o quiere una nueva (pero validamos)
             // En este diseño estricto, si ya tiene pregunta, NO debe girar de nuevo.
             echo json_encode(['error' => 'Ya tienes una pregunta asignada', 'redirect' => '/partida/jugarPregunta']);
             exit();
        }

        $userId = intval($_SESSION['usuario_id']);
        $difficultyLevel = $this->model->getDifficultyLevelByUserId($userId);

        // 1. Elegir categoría al azar
        // IDs: 1:Historia, 2:Ciencia, 3:Deportes, 4:Geografía, 5:Entretenimiento
        $categories = [
            ['id' => 1, 'name' => 'Historia'],
            ['id' => 2, 'name' => 'Ciencia'],
            ['id' => 3, 'name' => 'Deportes'],
            ['id' => 4, 'name' => 'Geografía'],
            ['id' => 5, 'name' => 'Entretenimiento']
        ];
        
        $selectedCategory = $categories[array_rand($categories)];
        
        // Obtener IDs de preguntas ya jugadas en esta partida
        $playedQuestions = [];
        if (isset($_SESSION['partida_respuestas']) && is_array($_SESSION['partida_respuestas'])) {
            foreach ($_SESSION['partida_respuestas'] as $respuesta) {
                if (isset($respuesta['question_id'])) {
                    $playedQuestions[] = $respuesta['question_id'];
                }
            }
        }

        // 2. Buscar pregunta de esa categoría y dificultad, excluyendo las jugadas
        $pregunta = $this->model->getQuestionByCategoryAndDifficulty($selectedCategory['id'], $difficultyLevel, $playedQuestions);

        if (!$pregunta) {
            // Fallback: buscar cualquier pregunta si no hay de esa categoría (raro)
             echo json_encode(['error' => 'No hay preguntas disponibles para esta categoría']);
             exit();
        }

        // 3. Guardar en sesión
        $_SESSION['current_question'] = $pregunta;

        // 4. Calcular ángulo para el frontend (esto debería coincidir con la lógica visual de JS)
        // En JS: 5 segmentos. 360 / 5 = 72 grados cada uno.
        // Orden en JS: Historia, Ciencia, Deportes, Geografía, Entretenimiento
        // Indices: 0, 1, 2, 3, 4
        // Ángulo objetivo: (Index * 72) + algo random dentro del segmento?
        // Mejor devolvemos el ID y que JS calcule el ángulo.
        
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
            // Si no hay pregunta, volver a la ruleta
            header("Location: /partida/ruleta");
            exit();
        }

        $pregunta = $_SESSION['current_question'];
        $questionId = intval($pregunta['question_id']);

        // Incrementar view_count (solo una vez por pregunta jugada? O cada vez que la ve? Mejor aquí)
        // Ojo: si recarga, se incrementa. Podríamos usar una flag en sesión 'viewed'.
        if (!isset($_SESSION['question_viewed_' . $questionId])) {
            $this->model->incrementarViewCount($questionId);
            $_SESSION['question_viewed_' . $questionId] = true;
        }

        // INICIO TEMPORIZADOR: Guardar tiempo de inicio
        $_SESSION['question_start_time'] = microtime(true);

        // Obtener respuestas
        $respuestas = $this->model->getAnswersByQuestionId($questionId);

        $this->renderer->render("jugarPregunta", [
            'pregunta' => $pregunta,
            'respuestas' => $respuestas,
            'pregunta_numero' => $_SESSION['partida_preguntas_jugadas'] + 1,
            'total_preguntas' => 10,
            'reportSuccess' => isset($_GET['report']) && $_GET['report'] === 'success',
            'reportError' => isset($_GET['reportError']) ? urldecode($_GET['reportError']) : null
        ]);
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

        // Validar que sea la pregunta actual
        if (!isset($_SESSION['current_question']) || $_SESSION['current_question']['question_id'] != $questionId) {
            header("Location: /partida/jugarPregunta");
            exit();
        }

        // VALIDACIÓN DE TIEMPO
        $tiempoAgotado = false;
        if (isset($_SESSION['question_start_time'])) {
            $tiempoTranscurrido = microtime(true) - $_SESSION['question_start_time'];
            // 10 segundos + 2 segundos de margen por latencia
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

        // Avanzar contador
        $_SESSION['partida_preguntas_jugadas']++;
        
        // Obtener texto de la respuesta correcta para mostrarlo
        $respuestaCorrectaTexto = "Desconocida";
        $respuestas = $this->model->getAnswersByQuestionId($questionId);
        foreach ($respuestas as $resp) {
            if ($resp['answer_id'] == $pregunta['correct_answer_id']) {
                $respuestaCorrectaTexto = $resp['answer_text'];
                break;
            }
        }

        // Guardar datos para la pantalla de feedback
        $_SESSION['last_result'] = [
            'esCorrecto' => $correcto,
            'tiempoAgotado' => $tiempoAgotado,
            'respuestaCorrecta' => $respuestaCorrectaTexto,
            'question_text' => $pregunta['question_text'],
            'question_id' => $questionId,
            'siguienteUrl' => ($_SESSION['partida_preguntas_jugadas'] >= 10) ? '/partida/resultados' : '/partida/ruleta'
        ];
        
        // Limpiar pregunta actual
        unset($_SESSION['current_question']);
        unset($_SESSION['question_start_time']);

        // Redirigir a la pantalla de respuesta (feedback)
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
            // Si no hay resultado guardado, probablemente recargó o entró directo.
            // Redirigir a donde corresponda según el estado del juego.
            if (isset($_SESSION['current_question'])) {
                header("Location: /partida/jugarPregunta");
            } else {
                header("Location: /partida/ruleta");
            }
            exit();
        }

        $data = $_SESSION['last_result'];
        
        // Agregar datos de reporte si existen en GET
        if (isset($_GET['report']) && $_GET['report'] === 'success') {
            $data['reportSuccess'] = true;
        }
        if (isset($_GET['reportError'])) {
            $data['reportError'] = urldecode($_GET['reportError']);
        }
        
        // Asegurar que usuario está disponible para el template
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
        $totalPreguntas = 10; // Fijo en 10

        // Actualizar estadísticas del usuario
        if (isset($_SESSION['usuario_id'])) {
            $userId = intval($_SESSION['usuario_id']);
            $estadisticas = $this->model->actualizarEstadisticasUsuario($userId, $puntos);
        }

        // Limpiar sesión de partida
        unset($_SESSION['partida_activa']);
        unset($_SESSION['partida_preguntas_jugadas']);
        unset($_SESSION['partida_puntos']);
        unset($_SESSION['partida_respuestas']);
        unset($_SESSION['current_question']);
        unset($_SESSION['last_result']); // Limpiar también el último resultado

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

        // Determinar a dónde redirigir
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
