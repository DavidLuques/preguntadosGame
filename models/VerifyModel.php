<?php

class VerifyModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function activarCuenta($token)
    {
        $conn = $this->conexion->getConnection();
        $token = $conn->real_escape_string($token);

        $sql = "SELECT id FROM user WHERE verify_token = '$token' LIMIT 1";
        $result = $conn->query($sql)->fetch_assoc();

        if (!$result) {
            return false; 
        }

        $userId = $result['id'];

        $sqlUpdate = "UPDATE user 
                      SET verify_token = NULL, email_verified = 1 
                      WHERE id = $userId";

        return $conn->query($sqlUpdate);
    }
}