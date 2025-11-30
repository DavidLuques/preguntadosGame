<?php
include_once(__DIR__ . "/../models/JugadoresModel.php");

class JugadoresController
{
    private $model;
    private $renderer;

    public function __construct($JugadorModel, $renderer)
    {
        $this->model = $JugadorModel;
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
        $this->listadoJugadores();
    }

    public function listadoJugadores()
    {
        $jugadores = $this->model->mostrarTabla();
        $this->renderer->render("listadoJugadores", ['jugadores' => $jugadores ?? []]);
    }

    public function sugerirPregunta()
    {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: /login/login");
            exit();
        }
        $this->renderer->render("sugerirPregunta", [
            'success' => isset($_GET['success']),
            'error' => isset($_GET['error']) ? urldecode($_GET['error']) : null
        ]);
    }

    public function procesarSugerencia()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /jugadores/sugerirPregunta");
            exit();
        }

        if (!isset($_SESSION['usuario_id'])) {
            header("Location: /login/login");
            exit();
        }

        $questionText = $_POST['question_text'] ?? '';
        $categoryId = $_POST['category_id'] ?? '';
        $correctAnswerIndex = $_POST['correct_answer'] ?? '';
        
        $answers = [
            1 => $_POST['answer_1'] ?? '',
            2 => $_POST['answer_2'] ?? '',
            3 => $_POST['answer_3'] ?? '',
            4 => $_POST['answer_4'] ?? ''
        ];

        // Validaciones básicas
        if (empty($questionText) || empty($categoryId) || empty($correctAnswerIndex)) {
            header("Location: /jugadores/sugerirPregunta?error=" . urlencode("Todos los campos son obligatorios"));
            exit();
        }

        foreach ($answers as $ans) {
            if (empty($ans)) {
                header("Location: /jugadores/sugerirPregunta?error=" . urlencode("Debes completar las 4 respuestas"));
                exit();
            }
        }

        // Guardar en BD
        $success = $this->model->crearPreguntaSugerida($questionText, $categoryId, $answers, $correctAnswerIndex);

        if ($success) {
            header("Location: /jugadores/sugerirPregunta?success=1");
        } else {
            header("Location: /jugadores/sugerirPregunta?error=" . urlencode("Error al guardar la sugerencia"));
        }
        exit();
    }
}