<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login MVC</title>
</head>
<body>
    <h2>Bienvenido</h2>
    <a href="views/login.php">Iniciar sesión</a> |
    <a href="views/registro.php">Registrarse</a>
</body>
</html>

<?php
require_once 'controllers/UsuarioController.php';

$controller = new UsuarioController();

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'registrar':
            $controller->registrar($_POST['nombre'], $_POST['password']);
            break;
        case 'login':
            $controller->login($_POST['password']);
            break;
    }
}

if (isset($_GET['logout'])) {
    session_start();
    session_destroy();
    header("Location: index.php");
}