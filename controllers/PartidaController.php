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

    public function partida()
    {
        if (isset($_SESSION['editor'])) {
            die('Acceso no autorizado. <a href="/">Volver al inicio</a>');
        }
        $this->renderer->render("partida");
    }
}