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
        if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'editor') {
            header("Location: /editor/dashboard");
            exit();
        }
        $this->listadoJugadores();
    }

    public function listadoJugadores()
    {
        $jugadores = $this->model->mostrarTabla();
        $this->renderer->render("listadoJugadores", ['jugadores' => $jugadores ?? []]);
    }
}