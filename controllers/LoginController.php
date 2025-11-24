<?php
include_once(__DIR__ . "/../models/LoginModel.php");
class LoginController
{

    private $model;
    private $renderer;

    public function __construct($model, $renderer)
    {
        $this->model = $model;
        $this->renderer = $renderer;
    }

    public function base()
    {
        $this->login();
    }

    public function login()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (!isset($_POST["nombre"]) || !isset($_POST["password"])) {
                $this->renderer->render("login", ["error" => "Faltan datos de inicio de sesión"]);
                return;
            }

            $resultado = $this->model->getUserWith($_POST["nombre"], $_POST["password"]);

            // Si el array no está vacío, el usuario existe
            if (!empty($resultado)) {
                $_SESSION["usuario"] = $_POST["nombre"];
                header("Location: /");
                exit();
            } else {
                $this->renderer->render("login", ["error" => "Usuario o clave incorrecta"]);
            }
        } else {
            $this->renderer->render("login");
        }
    }

    public function logout()
    {
        session_destroy();
        header("Location: /");
        exit();
    }

    public function registro()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (!isset($_POST["nombre"]) || !isset($_POST["password"])) {
                $this->renderer->render("registro", ["error" => "Faltan datos de registro"]);
                return;
            }

            $username = trim($_POST["nombre"]);
            $password = $_POST["password"];

            // Validar que el username no esté vacío
            if (empty($username)) {
                $this->renderer->render("registro", ["error" => "El nombre de usuario no puede estar vacío"]);
                return;
            }

            // Validar que la contraseña no esté vacía
            if (empty($password)) {
                $this->renderer->render("registro", ["error" => "La contraseña no puede estar vacía"]);
                return;
            }

            // Verificar si el usuario ya existe
            if ($this->model->usuarioExiste($username)) {
                $this->renderer->render("registro", ["error" => "El nombre de usuario ya está en uso"]);
                return;
            }

            // Registrar el usuario
            if ($this->model->registrarUsuario($username, $password)) {
                // Mostrar mensaje de éxito
                $this->renderer->render("registro", ["success" => true, "mensaje" => "¡Éxito! Usuario registrado correctamente"]);
            } else {
                $this->renderer->render("registro", ["error" => "Error al registrar el usuario. Por favor, intentá nuevamente"]);
            }
        } else {
            $success = isset($_GET['success']) && $_GET['success'] === 'registro';
            $this->renderer->render("registro", ['success' => $success]);
        }
    }
}
