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
            header("Location: /partida/mostrarPregunta?question_id=$questionId&reportError=$error");
            exit();
        }

        if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario_id'])) {
            header("Location: /login/login");
            exit();
        }

        $userId = intval($_SESSION['usuario_id']);
        $resultado = $this->model->guardarReportePregunta($questionId, $userId, $reason);

        if (isset($resultado['success']) && $resultado['success'] === true) {
            header("Location: /partida/mostrarPregunta?question_id=$questionId&report=success");
        } else {
            $mensajeError = isset($resultado['error'])
                ? $resultado['error']
                : 'No pudimos guardar tu reporte. Intentá nuevamente.';
            $error = urlencode($mensajeError);
            header("Location: /partida/mostrarPregunta?question_id=$questionId&reportError=$error");
        }

        exit();
    }
}
