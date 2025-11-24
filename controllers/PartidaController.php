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
        if (isset($_SESSION['editor'])) {
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

        $userId = intval($_SESSION['usuario_id']);
        
        $difficultyLevel = $this->model->getDifficultyLevelByUserId($userId);

        $preguntas = [];
        for ($i = 0; $i < 10; $i++) {
            $pregunta = $this->model->getQuestionByDifficulty($difficultyLevel);
            if ($pregunta) {
                $preguntas[] = $pregunta['question_id'];
            }
        }

        // Si no hay suficientes preguntas, completar con cualquier pregunta activa
        while (count($preguntas) < 10) {
            $sql = "SELECT question_id FROM question WHERE status = 'activa' ORDER BY RAND() LIMIT 1";
            $result = $this->model->conexion->query($sql);
            if ($result && !empty($result)) {
                $preguntas[] = $result[0]['question_id'];
            } else {
                break;
            }
        }

        // Inicializar partida en sesión
        $_SESSION['partida_activa'] = true;
        $_SESSION['partida_preguntas'] = $preguntas;
        $_SESSION['partida_pregunta_actual'] = 0;
        $_SESSION['partida_respuestas'] = [];
        $_SESSION['partida_puntos'] = 0;

        // Redirigir a la primera pregunta
        if (!empty($preguntas)) {
            header("Location: /partida/jugarPregunta");
            exit();
        } else {
            die('No hay preguntas disponibles para jugar.');
        }
    }

    public function jugarPregunta()
    {
        if (!isset($_SESSION['partida_activa']) || !$_SESSION['partida_activa']) {
            header("Location: /partida/inicioPartida");
            exit();
        }

        $preguntaActual = intval($_SESSION['partida_pregunta_actual']);
        $preguntas = $_SESSION['partida_preguntas'];

        if ($preguntaActual >= count($preguntas)) {
            // Partida completada, mostrar resultados
            header("Location: /partida/resultados");
            exit();
        }

        $questionId = intval($preguntas[$preguntaActual]);
        $pregunta = $this->model->getQuestionById($questionId);

        if (!$pregunta) {
            die('No se encontró la pregunta.');
        }

        // Incrementar view_count
        $this->model->incrementarViewCount($questionId);

        // Obtener respuestas
        $respuestas = $this->model->getAnswersByQuestionId($questionId);

        $this->renderer->render("jugarPregunta", [
            'pregunta' => $pregunta,
            'respuestas' => $respuestas,
            'pregunta_numero' => $preguntaActual + 1,
            'total_preguntas' => count($preguntas),
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

        if ($questionId <= 0 || $answerId <= 0) {
            header("Location: /partida/jugarPregunta");
            exit();
        }

        // Verificar que es la pregunta actual
        $preguntaActual = intval($_SESSION['partida_pregunta_actual']);
        $preguntas = $_SESSION['partida_preguntas'];
        
        if ($preguntaActual >= count($preguntas) || $preguntas[$preguntaActual] != $questionId) {
            header("Location: /partida/jugarPregunta");
            exit();
        }

        // Obtener pregunta y verificar respuesta
        $pregunta = $this->model->getQuestionById($questionId);
        $correcto = false;

        if ($pregunta && intval($pregunta['correct_answer_id']) === $answerId) {
            $correcto = true;
            $_SESSION['partida_puntos'] = intval($_SESSION['partida_puntos']) + 1;
            $this->model->incrementarCorrectAnswerCount($questionId);
        }

        $_SESSION['partida_respuestas'][] = [
            'question_id' => $questionId,
            'answer_id' => $answerId,
            'correcto' => $correcto
        ];

        $_SESSION['partida_pregunta_actual'] = $preguntaActual + 1;

        if ($_SESSION['partida_pregunta_actual'] >= count($preguntas)) {
            header("Location: /partida/resultados");
        } else {
            header("Location: /partida/jugarPregunta");
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
        $totalPreguntas = count($_SESSION['partida_preguntas']);

        // Actualizar estadísticas del usuario
        if (isset($_SESSION['usuario_id'])) {
            $userId = intval($_SESSION['usuario_id']);
            $estadisticas = $this->model->actualizarEstadisticasUsuario($userId, $puntos);
        }

        // Limpiar sesión de partida
        unset($_SESSION['partida_activa']);
        unset($_SESSION['partida_preguntas']);
        unset($_SESSION['partida_pregunta_actual']);
        unset($_SESSION['partida_respuestas']);

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
