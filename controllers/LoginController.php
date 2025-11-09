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
        $success = isset($_GET['success']) && $_GET['success'] === 'registro';
        $this->renderer->render("registro", ['success' => $success]);
    }
}
