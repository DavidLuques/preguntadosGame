<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController {
    private $usuario;

    public function __construct() {
        $database = new Database();
        $db = $database->conectar();
        $this->usuario = new Usuario($db);
    }

    public function registrar($nombre, $password) {
        $this->usuario->nombre = $nombre;
        $this->usuario->password = password_hash($password, PASSWORD_BCRYPT);

        if ($this->usuario->registrar()) {
            header("Location: ../index.php?success=registro");
        } else {
            echo "Error al registrar usuario.";
        }
    }

    public function login($nombre, $password) {
        $this->usuario->nombre = $nombre;
        $this->usuario->password = $password;

        $usuario = $this->usuario->login();

        if ($usuario) {
            session_start();
            $_SESSION['usuario'] = $usuario;
            header("Location: ../views/home.php?bienvenido=1");
        } else {
            header("Location: ../index.php?error=login");
        }
    }
}