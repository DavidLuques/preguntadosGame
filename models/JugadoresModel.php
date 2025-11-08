<?php

class JugadoresModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function mostrarTabla()
    {
        $sql = "SELECT username as usuario, name as nombre, lastname as apellido, 
                email as mail, country as pais, '' as ciudad, gender as sexo, 
                birth_year as nacimiento, created_at as fechaAlta, role as rol, 
                total_score as puntajeTotal, games_played as partidasJugadas, 
                games_won as partidasGanadas, match_lost as partidasPerdidas, 
                difficulty_level as nivelDificultad, profile_picture as fotoPerfil,
                username as id
                FROM user";
        $jugadores = $this->conexion->query($sql);
        return $jugadores;
    }
}