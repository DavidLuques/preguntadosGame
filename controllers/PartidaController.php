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

    public function mostrarPregunta()
    {
        $pregunta = null;

        if (isset($_GET['question_id'])) {
            $questionId = intval($_GET['question_id']);
            $pregunta = $this->model->getQuestionById($questionId);
            if (!$pregunta) {
                die('No se encontró la pregunta solicitada.');
            }
        } else {
            if (!isset($_GET['category_id'])) {
                die('Falta el parámetro category_id');
            }

            $categoryId = intval($_GET['category_id']);
            $pregunta = $this->model->getRandomQuestionByCategory($categoryId);

            if (!$pregunta || empty($pregunta)) {
                die('No se encontró ninguna pregunta para esa categoría');
            }

            $pregunta = $pregunta[0];
        }

        $this->renderer->render("mostrarPregunta", [
            'category_id' => $pregunta['category_id'],
            'question_text' => $pregunta['question_text'],
            'question_id' => $pregunta['question_id'],
            'reportSuccess' => isset($_GET['report']) && $_GET['report'] === 'success',
            'reportError' => isset($_GET['reportError']) ? urldecode($_GET['reportError']) : null
        ]);
    }

    public function partida()
    {
        if (isset($_SESSION['editor']) || (isset($_SESSION['rol']) && $_SESSION['rol'] === 'ADMIN')) {
            die('Acceso no autorizado. <a href="/">Volver al inicio</a>');
        }
        $this->renderer->render("partida");
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

        $this->renderer->render("partida"); // Vista de la ruleta
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
        
        // 2. Buscar pregunta de esa categoría y dificultad
        $pregunta = $this->model->getQuestionByCategoryAndDifficulty($selectedCategory['id'], $difficultyLevel);

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

        $pregunta = $_SESSION['current_question'];
        $correcto = false;

        if (intval($pregunta['correct_answer_id']) === $answerId) {
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
        
        // Limpiar pregunta actual para permitir girar ruleta de nuevo
        unset($_SESSION['current_question']);

        if ($_SESSION['partida_preguntas_jugadas'] >= 10) {
            header("Location: /partida/resultados");
        } else {
            // Volver a la ruleta para la siguiente pregunta
            header("Location: /partida/ruleta");
        }
        exit();
    }

    public function resultados()
    {
        if (!isset($_SESSION['partida_activa']) || !$_SESSION['partida_activa']) {
            header("Location: /partida/inicioPartida");
            exit();
        }

        $puntos = intval($_SESSION['partida_puntos']);
        $puntos = intval($_SESSION['partida_puntos']);
        $totalPreguntas = 10; // Fijo en 10

        // Actualizar estadísticas del usuario
        if (isset($_SESSION['usuario_id'])) {
            $userId = intval($_SESSION['usuario_id']);
            $estadisticas = $this->model->actualizarEstadisticasUsuario($userId, $puntos);
        }

        // Limpiar sesión de partida
        unset($_SESSION['partida_activa']);
        unset($_SESSION['partida_activa']);
        unset($_SESSION['partida_preguntas_jugadas']);
        unset($_SESSION['partida_puntos']);
        unset($_SESSION['partida_respuestas']);
        unset($_SESSION['current_question']);

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

        if (!$questionId) {
            header('Location: /partida');
            exit();
        }

        if ($reason === '') {
            $error = urlencode('Debés indicar un motivo para reportar la pregunta.');
            // Si hay partida activa, redirigir a jugarPregunta, sino a mostrarPregunta
            if (isset($_SESSION['partida_activa']) && $_SESSION['partida_activa']) {
                header("Location: /partida/jugarPregunta?reportError=$error");
            } else {
                header("Location: /partida/mostrarPregunta?question_id=$questionId&reportError=$error");
            }
            exit();
        }

        if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario_id'])) {
            header("Location: /login/login");
            exit();
        }

        $userId = intval($_SESSION['usuario_id']);
        $resultado = $this->model->guardarReportePregunta($questionId, $userId, $reason);

        // Determinar a dónde redirigir según si hay partida activa
        $tienePartidaActiva = isset($_SESSION['partida_activa']) && $_SESSION['partida_activa'];
        
        if (isset($resultado['success']) && $resultado['success'] === true) {
            if ($tienePartidaActiva) {
                header("Location: /partida/jugarPregunta?report=success");
            } else {
                header("Location: /partida/mostrarPregunta?question_id=$questionId&report=success");
            }
        } else {
            $mensajeError = isset($resultado['error'])
                ? $resultado['error']
                : 'No pudimos guardar tu reporte. Intentá nuevamente.';
            $error = urlencode($mensajeError);
            if ($tienePartidaActiva) {
                header("Location: /partida/jugarPregunta?reportError=$error");
            } else {
                header("Location: /partida/mostrarPregunta?question_id=$questionId&reportError=$error");
            }
        }

        exit();
    }
}
