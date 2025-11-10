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
        $this->listadoJugadores();
    }

    public function listadoJugadores()
    {
        $jugadores = $this->model->mostrarTabla();
        $this->renderer->render("listadoJugadores", ['jugadores' => $jugadores ?? []]);
    }
}