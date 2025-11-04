<?php

class LoginController
{
    private $conexion;
    private $renderer;

    public function __construct($conexion, $renderer)
    {
        $this->conexion = $conexion;
        $this->renderer = $renderer;
    }

    public function login()
    {
        $error = null;
        if (isset($_POST["nombre"]) && isset($_POST["password"])) {
            $usuario = trim($_POST["nombre"]);
            $password = trim($_POST["password"]);
            
            // Usar prepared statements para prevenir SQL Injection
            $conn = $this->conexion->getConnection();
            $stmt = $conn->prepare("SELECT * FROM user WHERE username = ? AND password = ?");
            $stmt->bind_param("ss", $usuario, $password);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                // Inicio de sesión exitoso
                $_SESSION["usuario"] = $usuario;
                $stmt->close();
                header("Location: index.php");
                exit();
            } else {
                // Error de inicio de sesión
                $error = "Usuario o contraseña incorrectos.";
            }
            if ($stmt) {
                $stmt->close();
            }
        }
        $this->renderer->render("login", ['error' => $error]);
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header("Location: index.php");
        exit();
    }

    public function registro()
    {
        /*if (isset($_GET['success']) && $_GET['success'] === 'registro') {
            echo "<p style='color:green;'>Usuario registrado correctamente.</p>";
        }*/

        $this->renderer->render("registro");
    }
}
