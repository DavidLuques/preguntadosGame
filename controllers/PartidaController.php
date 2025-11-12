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
        if (!isset($_GET['category_id'])) {
            die('Falta el parámetro category_id');
        }

        $categoryId = intval($_GET['category_id']);

        // Pedimos una pregunta al modelo
        $pregunta = $this->model->getRandomQuestionByCategory($categoryId);

        if (!$pregunta) {
            die('No se encontró ninguna pregunta para esa categoría');
        }

        // Renderizamos la vista con los datos
        $this->renderer->render("mostrarPregunta", [
            'category_id' => $pregunta['category_id'],
            'question_text' => $pregunta['question_text']
        ]);
    }

    public function partida()
    {
        if (isset($_SESSION['editor'])) {
            die('Acceso no autorizado. <a href="/">Volver al inicio</a>');
        }
        $this->renderer->render("partida");
    }
}
