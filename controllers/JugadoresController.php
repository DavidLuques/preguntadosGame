<?php

class JugadoresController
{
    private $conexion;
    private $renderer;

    public function __construct($conexion, $renderer)
    {
        $this->conexion = $conexion;
        $this->renderer = $renderer;
    }

    public function listadoJugadores()
    {
        $sql = "SELECT * FROM jugadores";
        $jugadores = $this->conexion->query($sql); // devuelve array directamente

        $this->renderer->render("listadoJugadores", ['jugadores' => $jugadores]);
    }

    public function partida()
    {
        $this->renderer->render("partida");
    }
}
