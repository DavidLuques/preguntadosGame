<?php
class MyConexion
{
    private $conexion;

    public function __construct($server, $user, $password, $database)
    {
        $this->conexion = new mysqli($server, $user, $password, $database);
        if ($this->conexion->error) {
            die('Error de la conexión (' . $this->conexion->error );
        }
    }

    public function query($sql)
    {
        $result = $this->conexion->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return null;
    }
}