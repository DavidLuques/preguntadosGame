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
        if (isset($_POST["usuario"]) && isset($_POST["password"])) {
            $usuario = $_POST["usuario"];
            $password = $_POST["password"];
            $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND password = '$password'";
            $resultado = $this->conexion->query($sql);
            if (sizeof($resultado) > 0) {
                // Inicio de sesión exitoso
                $_SESSION["usuario"] = $usuario;
                header("Location: /");
                echo "<p style='color:green;'>Inicio de sesión exitoso.</p>";
                exit();
            } else {
                // Error de inicio de sesión
                $error = "Usuario o contraseña incorrectos.";
            }
        }
        $this->renderer->render("login");
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header("Location: /");
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
