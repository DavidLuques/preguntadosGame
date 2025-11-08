<?php
include_once(__DIR__ . "/../models/LoginModel.php");
class LoginController
{
    private $conexion;
    private $renderer;
    private $model;

    public function __construct($conexion, $renderer)
    {
        $this->conexion = $conexion;
        $this->renderer = $renderer;
        $this->model = new LoginModel($conexion);
    }

    public function base()
    {
        $this->login();
    }

    public function login()
    {
        $error = $this->model->loginUser();;
        $this->renderer->render("login", ['error' => $error]);
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
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
