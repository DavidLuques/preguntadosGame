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
        $sql = "SELECT username as usuario, location as location, 
                total_score as puntajeTotal, profile_picture as fotoPerfil,
                id as id
                FROM user
                WHERE rol = 'JUGADOR' AND total_score IS NOT NULL
                ORDER BY total_score DESC
                LIMIT 7";
        
        $jugadores = $this->conexion->query($sql);
        
        if ($jugadores === null) {
            return [];
        }
        
        if (is_array($jugadores) && !empty($jugadores)) {
            $posicion = 1;
            foreach ($jugadores as &$jugador) {
                $jugador['posicion'] = $posicion;
                $posicion++;
            }
        }
        
        return $jugadores ?? [];
    }
}