<?php
class MyConexion
{
    private $conexion;

    public function __construct($server, $user, $password, $database)
    {
        $this->conexion = new mysqli($server, $user, $password, $database);
        if ($this->conexion->connect_error) {
            throw new Exception('Error de la conexión: ' . $this->conexion->connect_error);
        }
        if (!$this->conexion->set_charset("utf8mb4")) {
            throw new Exception('Error estableciendo charset: ' . $this->conexion->error);
        }
    }

    public function query($sql)
    {
        $result = $this->conexion->query($sql);
        
        if (!$result) {
            error_log("Error en consulta SQL: " . $this->conexion->error);
            return null;
        }

        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return [];
    }
    
    public function getConnection() {
        return $this->conexion;
    }
}