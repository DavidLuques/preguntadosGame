<?php
require_once 'controllers/UsuarioController.php';

$controller = new UsuarioController();

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'registrar':
            $controller->registrar($_POST['nombre'], $_POST['password']);
            exit;
        case 'login':
            $controller->login($_POST['nombre'], $_POST['password']);
            exit;
    }
}

if (isset($_GET['logout'])) {
    session_start();
    session_destroy();
    header("Location: index.php");
    exit;
}
?>

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

    <?php
    if (isset($_GET['success']) && $_GET['success'] === 'registro') {
        echo "<p style='color:green;'>Usuario registrado correctamente.</p>";
    }

    if (isset($_GET['error']) && $_GET['error'] === 'login') {
        echo "<p style='color:red;'>Nombre o contraseña incorrectos.</p>";
    }
    ?>
</body>
</html>