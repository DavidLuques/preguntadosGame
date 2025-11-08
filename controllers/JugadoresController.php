<?php
include_once(__DIR__ . "/../models/JugadoresModel.php");
class JugadoresController
{
    private $conexion;
    private $renderer;
    private $model;

    public function __construct($conexion, $renderer)
    {
        $this->conexion = $conexion;
        $this->renderer = $renderer;
        $this->model = new JugadoresModel($conexion);
    }

    public function base()
    {
        $this->listadoJugadores();
    }

    public function listadoJugadores()
    {
        $jugadores = $this->model->mostrarTabla();

        $this->renderer->render("listadoJugadores", ['jugadores' => $jugadores ? $jugadores : []]);
    }

    public function partida()
    {
        if(!isset($_SESSION['editor'])) {
            die('Acceso no autorizado. <a href="/">Volver al inicio</a>');
        }
        $this->renderer->render("partida");
    }
}