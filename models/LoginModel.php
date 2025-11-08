<?php

class LoginModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function loginUser()
    {
        $error = null;
        if (isset($_POST["nombre"]) && isset($_POST["password"])) {
            $usuario = trim($_POST["nombre"]);
            $password = trim($_POST["password"]);

            if (empty($usuario) || empty($password)) {
                $error = "Por favor completa todos los campos.";
            } else {
                // Usar prepared statements para prevenir SQL Injection
                $conn = $this->conexion->getConnection();
                $stmt = $conn->prepare("SELECT * FROM user WHERE username = ? AND password = ?");

                if ($stmt === false) {
                    error_log("Error preparando consulta: " . $conn->error);
                    $error = "Error del servidor. Por favor intenta más tarde.";
                } else {
                    $stmt->bind_param("ss", $usuario, $password);

                    if (!$stmt->execute()) {
                        error_log("Error ejecutando consulta: " . $stmt->error);
                        $error = "Error del servidor. Por favor intenta más tarde.";
                        $stmt->close();
                    } else {
                        $result = $stmt->get_result();

                        if ($result && $result->num_rows > 0) {
                            // Inicio de sesión exitoso
                            $_SESSION["usuario"] = $usuario;
                            $stmt->close();
                            header("Location: index.php");
                            exit();
                        } else {
                            // Error de inicio de sesión
                            $error = "Usuario o contraseña incorrectos.";
                            $stmt->close();
                        }
                    }
                }
            }
        }
        return $error;
    }
}