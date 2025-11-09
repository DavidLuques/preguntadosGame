<?php

class LoginModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function getUserWith($user, $password)
    {
        $sql = "SELECT * FROM user WHERE username = '$user' AND password = '$password'";
        $result = $this->conexion->query($sql);

        return $result ?? [];
    }
}