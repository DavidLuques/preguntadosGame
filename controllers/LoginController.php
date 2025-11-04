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
